<?php

namespace App\Core\Services;

use App\Core\Conductor;
use App\Models\CalificacionConductorModel;
use App\Models\ConductorModel;
use App\Models\PagoModel;
use App\Models\PersonaModel;
use App\Models\ViajeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            throw new \Exception('Solo los conductores pueden ver los viajes pendientes.', 403);
        }

        return ViajeModel::where('estado', 'Pendiente')->get();
    }

    public function aceptarViaje($viajeId)
    {
        $user = Auth::user();

        if ($user->rol !== 'Conductor') {
            throw new \Exception('Solo los conductores pueden aceptar un viaje.', 403);
        }

        $viaje = ViajeModel::where('id_viaje', $viajeId)->where('estado', 'Pendiente')->first();

        if (! $viaje) {
            throw new \Exception('Viaje no disponible.', 404);
        }

        $viaje->update([
            'conductor_id' => $user->id,
            'estado' => 'En curso',
        ]);

        return $viaje;
    }

    public function finalizarViaje()
    {
        $persona = Auth::user();

        if ($persona->rol !== 'Conductor') {
            throw new \Exception('Solo los conductores pueden finalizar viajes.', 403);
        }

        $viaje = ViajeModel::where('conductor_id', $persona->conductor->id_conductor)
            ->where('estado', 'En curso')
            ->first();

        if (! $viaje) {
            throw new \Exception('No hay viajes en curso.', 404);
        }

        DB::transaction(function () use ($viaje, $persona) {
            $pasajero = $viaje->pasajero;
            $comision = $viaje->tarifa * 0.1;
            $monto_conductor = $viaje->tarifa - $comision;

            PagoModel::create([
                'viaje_id' => $viaje->id_viaje,
                'monto_total' => $viaje->tarifa,
                'comision' => $comision,
                'monto_conductor' => $monto_conductor,
                'fecha' => now(),
            ]);

            if ($viaje->metodo === 'Efectivo') {
                $viaje->estado = 'Viaje pagado sin confirmar por el conductor';
            } else {
                $persona->increment('billetera', $monto_conductor);
                $viaje->estado = 'Completado';
                $pasajero->decrement('billetera', $viaje->tarifa);
                $persona->conductor->disponible = true;
            }

            $viaje->saldo_bloqueado = 0;
            $viaje->save();
        });

        return ['mensaje' => 'Viaje finalizado correctamente.'];
    }

    public function confirmarPago()
    {
        $persona = Auth::user();

        if ($persona->rol !== 'Conductor') {
            throw new \Exception('Solo los conductores pueden confirmar pagos.', 403);
        }

        $viaje = ViajeModel::where('conductor_id', $persona->conductor->id_conductor)
            ->where('estado', 'Viaje pagado sin confirmar por el conductor')
            ->first();

        if (! $viaje) {
            throw new \Exception('No hay viajes en espera de confirmación de pago.', 404);
        }

        $comision = (float) ($viaje->tarifa * 0.1);
        $monto_conductor = $viaje->tarifa - $comision;

        if ($persona->billetera < $comision) {
            throw new \Exception('El conductor no tiene saldo suficiente para pagar la comisión.', 400);
        }

        $persona->decrement('billetera', $comision);
        $viaje->estado = 'Completado';
        $persona->conductor->disponible = true;
        $persona->conductor->save();
        $viaje->save();

        PagoModel::create([
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => $viaje->tarifa,
            'comision' => $comision,
            'monto_conductor' => $monto_conductor,
            'fecha' => now(),
        ]);

        return ['mensaje' => 'Pago confirmado. Viaje marcado como completado.'];
    }

    public function calcularPromedioCalificaciones()
    {
        $persona = Auth::user(); // Obtener el conductor autenticado
        // dd($persona->conductor->id_conductor);
        $calificaciones = CalificacionConductorModel::where('conductor_id', $persona->conductor->id_conductor)->get(); // Obtener todas las calificaciones
        if ($calificaciones->isEmpty()) {
            return 0;
        }

        $promedio = $calificaciones->avg('calificacion', 2);

        return round($promedio, 0);
    }

    public function verHistorialViajesConductor()
    {
        $persona = Auth::user(); // Obtener el conductor autenticado

        $viajes = ViajeModel::where('conductor_id', $persona->conductor->id_conductor)->get(); // Obtener los viajes del conductor
        if ($viajes->isEmpty()) {
            return 0;
        }

        return $viajes;
    }
}
