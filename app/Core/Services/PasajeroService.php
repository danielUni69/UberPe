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

        // Verificar que el usuario sea una instancia de PersonaModel
        if (! $user instanceof PersonaModel) {
            throw new \Exception('El usuario no es una instancia de PersonaModel.');
        }

        // Verificar si hay conductores disponibles
        $conductorDisponible = ConductorModel::where('disponible', true)->first();
        if (! $conductorDisponible) {
            throw new \Exception('No hay conductores disponibles.', 400);
        }

        // Validar el método de pago
        if (! in_array($metodo_pago, ['Efectivo', 'Tarjeta', 'Billetera'])) {
            throw new \Exception('Método de pago no válido.', 400);
        }

        // Verificar saldo suficiente si el método de pago es Tarjeta o Billetera
        if (in_array($metodo_pago, ['Tarjeta', 'Billetera'])) {
            if ($user->billetera < $tarifa) {
                throw new \Exception('No tienes saldo suficiente en tu billetera.', 400);
            }
            $user->billetera -= $tarifa;
            $user->save();
        }

        // Crear el viaje
        $viaje = ViajeModel::create([
            'pasajero_id' => $user->id_persona,
            'conductor_id' => null, // Aún no se asigna un conductor
            'origen' => $origen,
            'destino' => $destino,
            'fecha' => now(),
            'estado' => 'Pendiente',
            'tarifa' => $tarifa,
            'metodo' => $metodo_pago,
            'saldo_bloqueado' => $tarifa,
        ]);

        return $viaje; // Devuelve el viaje creado
    }

    public function pagar()
    {
        $user = Auth::user();

        // Verificar que el usuario sea un pasajero
        if ($user->rol !== 'Pasajero') {
            throw new \Exception('Solo los pasajeros pueden pagar viajes.', 403);
        }

        // Buscar el primer viaje completado sin pagar
        $viaje = ViajeModel::where('pasajero_id', $user->id_persona)
            ->where('estado', 'Completado sin pagar')
            ->first();

        if (! $viaje) {
            throw new \Exception('No hay viajes para pagar.', 404);
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

            return [
                'mensaje' => 'Pago registrado correctamente.',
                'pago' => $pago,
            ];
        } else {
            $viaje->update(['estado' => 'Completado']);

            return [
                'mensaje' => 'Pago realizado. ¿Desea calificar al conductor?',
                'viaje' => $viaje,
            ];
        }
    }

    public function calificarConductor($id_conductor, $calificacion)
    {
        $user = Auth::user();

        if ($user->rol !== 'Pasajero') {
            throw new \Exception('Solo los pasajeros pueden calificar.', 403);
        }

        if ($calificacion < 1 || $calificacion > 5) {
            throw new \Exception('La calificación debe estar entre 1 y 5.', 400);
        }

        $conductor = ConductorModel::find($id_conductor);
        if (! $conductor) {
            throw new \Exception('Conductor no encontrado.', 404);
        }

        CalificacionConductorModel::create([
            'conductor_id' => $conductor->id_conductor,
            'calificacion' => $calificacion,
            'fecha' => now(),
        ]);

        return ['mensaje' => 'Calificación registrada correctamente.'];
    }

    public function verHistorialViajesPasajero()
    {
        $user = Auth::user();

        if ($user->rol !== 'Pasajero') {
            throw new \Exception('Solo los pasajeros pueden ver su historial.', 403);
        }

        $viajes = ViajeModel::where('pasajero_id', $user->id_persona)->get();

        return $viajes;
    }
}
