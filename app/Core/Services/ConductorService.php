<?php

namespace App\Core\Services;

use App\Core\Conductor;
use App\Models\CalificacionConductorModel;
use App\Models\ConductorModel;
use App\Models\Pago;
use App\Models\PagoModel;
use App\Models\PersonaModel;

use App\Models\ViajeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ConductorService
{
    public function getConductores()
    {
        return ConductorModel::with('persona')->get();
    }

    /**
     * Crea un nuevo conductor.
     *
     * @return ConductorModel
     */
    public function add(Conductor $conductor)
    {
        // Primero, creamos la Persona
        $personaModel = new PersonaModel;
        $personaModel->ci = $conductor->getCi();
        $personaModel->nombres = $conductor->getNombres();
        $personaModel->apellidos = $conductor->getApellidos();
        $personaModel->telefono = $conductor->getTelefono();
        $personaModel->email = $conductor->getEmail();
        $personaModel->usuario = $conductor->getUsuario();
        $personaModel->password = Hash::make($conductor->getPassword()); // Encriptar la contraseña
        $personaModel->rol = 'conductor'; // Rol fijo para conductores
        $personaModel->billetera = $conductor->getBilletera();
        $personaModel->deuda = $conductor->getDeuda();
        $personaModel->save();

        // Luego, creamos el Conductor
        $conductorModel = new ConductorModel;
        $conductorModel->persona_id = $personaModel->id_persona;
        $conductorModel->licencia = $conductor->getLicencia();
        $conductorModel->disponible = $conductor->getDisponible();
        $conductorModel->save();

        return $conductorModel;
    }

    /**
     * Edita un conductor existente.
     *
     * @param  int  $id
     * @return ConductorModel
     */
    public function edit($id, Conductor $conductor)
    {
        // Buscar la Persona asociada al conductor
        $personaModel = PersonaModel::find($id);

        if ($personaModel) {
            // Actualizar los campos de Persona
            $personaModel->ci = $conductor->getCi();
            $personaModel->nombres = $conductor->getNombres();
            $personaModel->apellidos = $conductor->getApellidos();
            $personaModel->telefono = $conductor->getTelefono();
            $personaModel->email = $conductor->getEmail();
            $personaModel->usuario = $conductor->getUsuario();

            // Si se proporciona una nueva contraseña, se encripta
            if ($conductor->getPassword()) {
                $personaModel->password = Hash::make($conductor->getPassword());
            }

            $personaModel->rol = 'conductor'; // Rol fijo para conductores
            $personaModel->billetera = $conductor->getBilletera();
            $personaModel->deuda = $conductor->getDeuda();
            $personaModel->save();

            // Actualizar los campos de Conductor
            $conductorModel = ConductorModel::where('persona_id', $personaModel->id_persona)->first();

            if ($conductorModel) {
                $conductorModel->licencia = $conductor->getLicencia();
                $conductorModel->disponible = $conductor->getDisponible();
                $conductorModel->save();

                return $conductorModel;
            }
        }

    }

    public function delete($id)
    {
        $conductorModel = ConductorModel::find($id);
        if ($conductorModel) {
            $personaModel = PersonaModel::find($conductorModel->persona_id);
            if ($personaModel) {
                $personaModel->delete();
            }
            $conductorModel->delete();
        }
    }

    public function viajesPendientes()
    {
        $user = Auth::user();
        if ($user->rol !== 'Conductor') {
            return response()->json(['mensaje' => 'Solo los conductores pueden ver los viajes pendientes.'], 403);
        }

        $viajes = ViajeModel::where('estado', 'Pendiente')->get();

        return response()->json($viajes);
    }

    public function aceptarViaje($viajeId)
    {
        $user = Auth::user();
        if ($user->rol === 'Pasajero') {
            return response()->json(['mensaje' => 'Solo los conductores pueden aceptar un viaje.'], 403);
        }

        $viaje = ViajeModel::where('id_viaje', $viajeId)->where('estado', 'Pendiente')->first();

        if (! $viaje) {
            return response()->json(['mensaje' => 'Viaje no disponible.'], 404);
        }

        $viaje->update([
            'conductor_id' => $user->id,
            'estado' => 'En curso',
        ]);

        return response()->json(['mensaje' => 'Has aceptado el viaje.', 'viaje' => $viaje]);
    }

    public function finalizarViaje()
    {
        $persona = Auth::user();
 
        if ($persona->rol !== 'Conductor') {
            return response()->json(['mensaje' => 'Solo los conductores pueden finalizar viajes.'], 403);
        }
        // Buscar un viaje en curso asignado al conductor
        $viaje = ViajeModel::where('conductor_id', $persona->conductor->id_conductor)
            ->where('estado', 'En curso')
            ->first();

        if (! $viaje) {
            return response()->json(['mensaje' => 'No hay viajes en curso.'], 404);
        }

        DB::transaction(function () use ($viaje, $persona ) {
            $pasajero = $viaje->pasajero;
            $comision = $viaje->tarifa * 0.1; // Comisión del 10%
            $monto_conductor = $viaje->tarifa - $comision; // Ganancia del conductor

            // Registrar el pago del viaje
            PagoModel::create([
                'viaje_id' => $viaje->id_viaje,
                'monto_total' => $viaje->tarifa,
                'comision' => $comision,
                'monto_conductor' => $monto_conductor,
                'fecha' => now(),
            ]);

            if ($viaje->metodo === 'Efectivo') {
                // En efectivo, el pago queda pendiente de confirmación
                $viaje->estado = 'Viaje pagado sin confirmar por el conductor';
            } else {
                // Pagos con Billetera o Tarjeta
                $persona->increment('billetera', $monto_conductor); // Se paga al conductor
                $viaje->estado = 'Completado';
                $pasajero->decrement('billetera', $viaje->tarifa); // Descontar del pasajero
                $persona->conductor->disponible = true; // Liberar al conductor
            }

            // Liberar saldo bloqueado
            $viaje->saldo_bloqueado = 0;
            $viaje->save();
        });

        return response()->json(['mensaje' => 'Viaje finalizado correctamente.']);
    }

    public function confirmarPago()
    {
        $persona = Auth::user(); // Obtener el usuario autenticado (que es un conductor)

        if ($persona->rol !== 'Conductor') {
            return response()->json(['mensaje' => 'Solo los conductores pueden confirmar pagos.'], 403);
        }

        // Obtener el viaje con estado "Viaje completado sin confirmar pago"
        $viaje = ViajeModel::where('conductor_id', $persona->condutor->id_conductor)
            ->where('estado', 'Viaje pagado sin confirmar por el conductor')
            ->first();

        if (! $viaje) {
            return response()->json(['mensaje' => 'No hay viajes en espera de confirmación de pago.'], 404);
        }

        $comision = (float) ($viaje->tarifa * 0.1); // Comisión del 10%
        $monto_conductor = $viaje->tarifa - $comision;

        // Verificar si el conductor tiene saldo suficiente para la comisión
        if ($persona->billetera >= $comision) {
            $persona->decrement('billetera', $comision); // Restar la comisión del saldo del conductor
            $viaje->estado = 'Completado'; // Cambiar el estado del viaje
            $persona->conductor->disponible = true; // Liberar al conductor
            $viaje->save(); // Guardar los cambios del viaje

            // Crear el pago registrado
            PagoModel::create([
                'viaje_id' => $viaje->id_viaje,
                'monto_total' => $viaje->tarifa,
                'comision' => $comision,
                'monto_conductor' => $monto_conductor,
                'fecha' => now(),
            ]);

            return response()->json(['mensaje' => 'Pago confirmado. Viaje marcado como completado.']);
        } else {
            return response()->json(['mensaje' => 'El conductor no tiene saldo suficiente para pagar la comisión.'], 400);
        }
    }

    public function calcularPromedioCalificaciones()
    {
        $conductor = Auth::user(); // Obtener el conductor autenticado

        $calificaciones = CalificacionConductorModel::where('conductor_id', $conductor->id)->get(); // Obtener todas las calificaciones

        if ($calificaciones->isEmpty()) {
            return 0;
        }

        $promedio = $calificaciones->avg('calificacion'); // Calcular el promedio de las calificaciones

        return response()->json(['promedio_calificaciones' => $promedio]);
    }

    public function verHistorialViajesConductor()
    {
        $conductor = Auth::user(); // Obtener el conductor autenticado

        $viajes = ViajeModel::where('conductor_id', $conductor->id)->get(); // Obtener los viajes del conductor
        if ($viajes->isEmpty()) {
            return response()->json(['mensaje' => 'No tienes viajes registrados.'], 404);
        }
        $historial = $viajes->map(function ($viaje) {
            return [
                'origen' => $viaje->origen,
                'destino' => $viaje->destino,
                'estado' => $viaje->estado,
                'tarifa' => $viaje->tarifa,
            ];
        });

        return response()->json(['historial_viajes' => $historial]);
    }
}
