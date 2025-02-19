<?php

namespace App\Core\Services;

class PasajeroService
{
    public function solicitarServicio(Request $request)
    {
        $user = Auth::user();

        if (session('rol') !== 'pasajero') {
            return response()->json(['mensaje' => 'Solo los pasajeros pueden solicitar un servicio.'], 403);
        }

        // Crear viaje sin asignar conductor
        $viaje = Viaje::create([
            'pasajero_id' => $user->id,
            'conductor_id' => null, // Aún sin asignar
            'origen' => $request->origen,
            'destino' => $request->destino,
            'fecha' => now(),
            'estado' => 'Pendiente',
            'tarifa' => $request->tarifa,
            'metodo_pago' => $request->metodo_pago,
            'saldo_bloqueado' => $request->tarifa,
        ]);

        return response()->json(['mensaje' => 'Solicitud de viaje creada.', 'viaje' => $viaje], 200);
    }

    public function pagar()
    {
        $user = Auth::user();

        if (session('rol') !== 'pasajero') {
            return response()->json(['mensaje' => 'Solo los pasajeros pueden pagar viajes.'], 403);
        }

        // Buscar el primer viaje completado sin pagar
        $viaje = Viaje::where('pasajero_id', $user->id)
            ->where('estado', 'Completado sin pagar')
            ->first();

        if (! $viaje) {
            return response()->json(['mensaje' => 'No hay viajes para pagar.'], 404);
        }

        // Crear el pago
        $comision = $viaje->tarifa * 0.1;
        $montoFinal = $viaje->tarifa - $comision;

        $pago = Pago::create([
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

    public function calificarConductor(Request $request, $conductorId)
    {
        $user = Auth::user();

        if (session('rol') !== 'pasajero') {
            return response()->json(['mensaje' => 'Solo los pasajeros pueden calificar.'], 403);
        }

        $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
        ]);

        $conductor = User::where('id', $conductorId)->where('rol', 'conductor')->first();

        if (! $conductor) {
            return response()->json(['mensaje' => 'Conductor no encontrado.'], 404);
        }

        CalificacionConductorModel::create([
            'conductor_id' => $conductor->id,
            'calificacion' => $request->calificacion,
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

        $viajes = Viaje::where('pasajero_id', $user->id)->get();

        return response()->json(['historial' => $viajes]);
    }
}
