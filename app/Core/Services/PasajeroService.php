<?php

namespace App\Core\Services;

use App\Models\CalificacionConductorModel;
use App\Models\ConductorModel;
use App\Models\PagoModel;
use App\Models\PersonaModel;
use App\Models\ViajeModel;
use Illuminate\Support\Facades\Auth;

class PasajeroService
{
    public function solicitarServicio($origen, $destino, $metodo_pago, $tarifa)
    {
        $user = Auth::user();
        if (! $user instanceof PersonaModel) {
            throw new \Exception('El usuario no es una instancia de PersonaModel.');
        }
        $conductorDisponible = ConductorModel::where('disponible', true)->first();

        if (! $conductorDisponible) {
            return response()->json(['mensaje' => 'No hay conductores disponibles.'], 400);
        }

        if (! in_array($metodo_pago, ['Efectivo', 'Tarjeta', 'Billetera'])) {
            return response()->json(['mensaje' => 'Método de pago no válido.'], 400);
        }

        if (in_array($metodo_pago, ['Tarjeta', 'Billetera'])) {
            if ($user->billetera < $tarifa) {
                return response()->json(['mensaje' => 'No tienes saldo suficiente en tu billetera.'], 400);
            }
            $user->billetera -= $tarifa;
            $user->save();
        }

        // Create a new ViajeModel instance
        $viaje = ViajeModel::create([
            'pasajero_id' => $user->id_persona,
            'conductor_id' => null,
            'origen' => $origen,
            'destino' => $destino,
            'fecha' => now(),
            'estado' => 'Pendiente',
            'tarifa' => $tarifa,
            'metodo' => $metodo_pago,
            'saldo_bloqueado' => $tarifa,
        ]);

        return response()->json([
            'mensaje' => 'Solicitud de viaje creada exitosamente.',
            'viaje' => $viaje,
        ], 201);
    }

    public function pagar()
    {
        $user = Auth::user();

        if ($user->rol !== 'Pasajero') {
            return response()->json(['mensaje' => 'Solo los pasajeros pueden pagar viajes.'], 403);
        }

        // Buscar el primer viaje completado sin pagar
        $viaje = ViajeModel::where('pasajero_id', $user->id_persona)
            ->where('estado', 'Completado sin pagar')
            ->first();

        if (! $viaje) {
            return response()->json(['mensaje' => 'No hay viajes para pagar.'], 404);
        }

        // Crear el pago
        $comision = $viaje->tarifa * 0.1;
        $montoFinal = $viaje->tarifa - $comision;

        $pago = PagoModel::create([
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => $viaje->tarifa,
            'comision' => $comision,
            'monto_conductor' => $montoFinal,
            'fecha' => now(),
        ]);

        // Actualizar estado del viaje
        if ($viaje->metodo === 'Efectivo') {
            $viaje->update(['estado' => 'Viaje pagado sin confirmar por el conductor']);
        } else {
            $viaje->update(['estado' => 'Completado']);

            return response()->json([
                'mensaje' => 'Pago realizado. ¿Desea calificar al conductor?',
                'viaje' => $viaje,
            ]);
        }

        return response()->json(['mensaje' => 'Pago registrado correctamente.', 'pago' => $pago]);
    }

    public function calificarConductor($id_conductor, $calificacion)
    {
        $user = Auth::user();

        if ($user->rol !== 'Pasajero') {
            return response()->json(['mensaje' => 'Solo los pasajeros pueden calificar.'], 403);
        }

        /*$calificacion->validate([
            'calificacion' => 'required|integer|min:1|max:5',
        ]);*/

        $conductor = ConductorModel::where('id_conductor', $id_conductor)->first();
        if (! $conductor) {
            return response()->json(['mensaje' => 'Conductor no encontrado.'], 404);
        }

        CalificacionConductorModel::create([
            'conductor_id' => $conductor->id_conductor,
            'calificacion' => $calificacion,
            'fecha' => now(),
        ]);

        return response()->json(['mensaje' => 'Calificación registrada correctamente.']);
    }

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
