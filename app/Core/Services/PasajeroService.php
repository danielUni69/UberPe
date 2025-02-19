<?php
namespace App\Core\Services;

use App\Models\ConductorModel;
use App\Models\PagoModel;
use App\Models\ViajeModel;
use App\Models\CalificacionConductorModel;
use Illuminate\Support\Facades\Auth;
use App\Models\PersonaModel;



class PasajeroService
{
    public function solicitarServicio($request)
    {
        // Get the authenticated user (pasajero)
        $user = Auth::user();
        // Find an available conductor
        if (!$user instanceof PersonaModel) {
            throw new \Exception("El usuario no es una instancia de PersonaModel.");
        }
        $conductorDisponible = ConductorModel::where('disponible', true)->first();

        if (!$conductorDisponible) {
            return response()->json(['mensaje' => 'No hay conductores disponibles.'], 400);
        }

        // Validate input data (tarifa and metodo_pago)
        $tarifa = $request->input('tarifa');
        $metodo_pago = $request->input('metodo_pago');

        if (!in_array($metodo_pago, ['Efectivo', 'Tarjeta', 'Billetera'])) {
            return response()->json(['mensaje' => 'Método de pago no válido.'], 400);
        }

        // Handle payment logic for Tarjeta or Billetera
        if (in_array($metodo_pago, ['Tarjeta', 'Billetera'])) {
            if ($user->billetera < $tarifa) {
                return response()->json(['mensaje' => 'No tienes saldo suficiente en tu billetera.'], 400);
            }
            // Deduct the fare from the pasajero's billetera
            $user->billetera -= $tarifa;
            $user->save();
        }

        // Create a new ViajeModel instance
        $viaje = ViajeModel::create([
            'pasajero_id' => $user->id_persona,
            'conductor_id' => $conductorDisponible->id_conductor,
            'origen' => $request->input('origen'),
            'destino' => $request->input('destino'),
            'fecha_inicio' => now(),
            'estado' => 'Pendiente',
            'tarifa' => $tarifa,
            'metodo_pago' => $metodo_pago,
            'saldo_bloqueado' => $tarifa,
        ]);

        // Mark the conductor as unavailable
        $conductorDisponible->update(['disponible' => false]);

        return response()->json([
            'mensaje' => 'Solicitud de viaje creada exitosamente.',
            'viaje' => $viaje,
        ], 201);
    }

    public function pagar()
    {
        $user = Auth::user();

        if (session('rol') !== 'pasajero') {
            return response()->json(['mensaje' => 'Solo los pasajeros pueden pagar viajes.'], 403);
        }

        // Buscar el primer viaje completado sin pagar
        $viaje = ViajeModel::where('pasajero_id', $user->id)
            ->where('estado', 'Completado sin pagar')
            ->first();

        if (! $viaje) {
            return response()->json(['mensaje' => 'No hay viajes para pagar.'], 404);
        }

        // Crear el pago
        $comision = $viaje->tarifa * 0.1;
        $montoFinal = $viaje->tarifa - $comision;

        $pago = PagoModel::create([
            'viaje_id' => $viaje->id,
            'pasajero_id' => $user->id,
            'monto_total' => $viaje->tarifa,
            'comision' => $comision,
            'monto_conductor' => $montoFinal,
            'fecha' => now(),
        ]);

        // Actualizar estado del viaje
        if ($viaje->metodo_pago === 'Efectivo') {
            $viaje->update(['estado' => 'Viaje pagado sin confirmar por el conductor']);
        } else {
            $viaje->update(['estado' => 'Pagado']);

            return response()->json([
                'mensaje' => 'Pago realizado. ¿Desea calificar al conductor?',
                'viaje' => $viaje,
            ]);
        }

        return response()->json(['mensaje' => 'Pago registrado correctamente.', 'pago' => $pago]);
    }

    /*public function calificarConductor($request)
    {
        $user = Auth::user();

        if (session('rol') !== 'pasajero') {
            return response()->json(['mensaje' => 'Solo los pasajeros pueden calificar.'], 403);
        }

        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
        ]);

        $conductor = ConductorModel::where('id', $conductorId)->where('rol', 'conductor')->first();

        if (! $conductor) {
            return response()->json(['mensaje' => 'Conductor no encontrado.'], 404);
        }

        CalificacionConductorModel::create([
            'conductor_id' => $conductor->id,
            'calificacion' => $request->calificacion,
            'fecha' => now(),
        ]);

        return response()->json(['mensaje' => 'Calificación registrada correctamente.']);
    }*/

    public function verHistorialViajesPasajero()
    {
        $user = Auth::user();

        if (session('rol') !== 'pasajero') {
            return response()->json(['mensaje' => 'Solo los pasajeros pueden ver su historial.'], 403);
        }

        $viajes = ViajeModel::where('pasajero_id', $user->id)->get();

        return response()->json(['historial' => $viajes]);
    }
}
