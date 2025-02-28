<?php

namespace App\Core\Services;

use App\Core\Persona;
use App\Models\PersonaModel;
use App\Models\Viaje;
use App\Models\ViajeModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PersonaService
{
    public function getPersonas()
    {
        return PersonaModel::all();
    }

    /**
     * Crea un nuevo usuario y encripta la contraseña.
     *
     * @return PersonaModel
     */
    public function add(Persona $persona)
    {
        $personaModel = new PersonaModel;
        $personaModel->ci = $persona->getCi();
        $personaModel->nombres = $persona->getNombres();
        $personaModel->apellidos = $persona->getApellidos();
        $personaModel->telefono = $persona->getTelefono();
        $personaModel->email = $persona->getEmail();
        $personaModel->usuario = $persona->getUsuario();
        $personaModel->password = Hash::make($persona->getPassword());
        $personaModel->rol = $persona->getRol();
        $personaModel->billetera = $persona->getBilletera();
        $personaModel->deuda = $persona->getDeuda();
        $personaModel->foto = $persona->getFoto(); // Añadir el atributo foto
        $personaModel->save();

        return $personaModel;
    }

    /**
     * Edita una persona existente.
     *
     * @param  int  $id
     * @return PersonaModel
     */
    public function editPersona(Persona $persona, $id)
    {
        // Buscar la persona por su ID
        $personaModel = PersonaModel::find($id);

        if ($personaModel) {
            // Actualizar los campos
            $personaModel->ci = $persona->getCi();
            $personaModel->nombres = $persona->getNombres();
            $personaModel->apellidos = $persona->getApellidos();
            $personaModel->telefono = $persona->getTelefono();
            $personaModel->email = $persona->getEmail();
            $personaModel->usuario = $persona->getUsuario();
            $personaModel->rol = $persona->getRol();
            $personaModel->billetera = $persona->getBilletera();
            $personaModel->foto = $persona->getFoto(); // Añadir el atributo foto
            $personaModel->save();

            return $personaModel;
        }

        throw new \Exception('Persona no encontrada.');
    }

    /**
     * Inicia sesión y almacena el usuario en la sesión con Auth.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function iniciarSesion($usuario, $password)
    {
        if (Auth::attempt(['usuario' => $usuario, 'password' => $password])) {
            $user = Auth::user();
            session(['rol' => $user->rol]);

            return true;
        }

        return false;
    }

    /**
     * Cierra la sesión delnusuario.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cerrarSesion()
    {
        Auth::logout();
        session()->forget('rol');

        return true; 
    }

    public function cambiarPass($currentPassword, $newPassword)
    {
        $user = Auth::user();

        if (Hash::check($currentPassword, $user->password)) {
            $user->password = Hash::make($newPassword);
            $user->save();
            return ['success' => true, 'message' => 'Contraseña cambiada exitosamente.'];
        } else {
            return ['success' => false, 'message' => 'La contraseña actual no es correcta.'];
        }
    }

    public function verBilletera($id)
    {
        $personaModel = PersonaModel::find($id);
        if ($personaModel) {
            return $personaModel->billetera;
        }

        return null;
    }

    public function recargarBilletera($id, $monto)
    {
        $personaModel = PersonaModel::find($id);
        if ($personaModel) {
            $personaModel->billetera += $monto;
            $personaModel->save();

            return $personaModel->billetera;
        }

        return null;
    }

    /**
     * Obtiene una persona por su ID.
     *
     * @param  int  $id
     * @return PersonaModel|null
     */
    public function getPersona($id)
    {
        $persona = PersonaModel::find($id);
        if (! $persona) {
            return null;
        }

        return $persona->convertToPersona();
    }

    public function cancelarViaje()
    {
        $user = Auth::user();
        $rol = session('rol') ?? $user->rol;

        // Verificar permisos
        if (! in_array($rol, ['Pasajero', 'Conductor'])) {
            throw new \Exception('No tienes permisos para cancelar viajes.', 403);
        }

        // Buscar el viaje activo
        $viaje = ViajeModel::where(function ($query) use ($user, $rol) {
            if ($rol === 'Pasajero') {
                $query->where('pasajero_id', $user->id_persona);
            } elseif ($rol === 'Conductor') {
                $query->where('conductor_id', $user->id_persona);
            }
        })
            ->whereIn('estado', ['En curso', 'Pendiente'])
            ->first();

        if (! $viaje) {
            throw new \Exception('No tienes viajes activos para cancelar.', 400);
        }

        // Lógica de cancelación
        if (in_array($viaje->metodo, ['Billetera', 'Tarjeta'])) {
            if ($rol === 'Pasajero') {
                if ($viaje->conductor) {
                    $comision = $viaje->saldo_bloqueado * 0.1; // 10% de comisión para el conductor
                    $montoDevuelto = $viaje->saldo_bloqueado - $comision; // 90% que se devuelve al pasajero

                    // Incrementar el saldo del conductor con la comisión
                    $viaje->conductor->persona->billetera += $comision;
                    $viaje->conductor->persona->save();

                    // Devolver el 90% al saldo del pasajero
                    $user->billetera += $montoDevuelto;
                    $user->save();

                    // Marcar al conductor como disponible
                    $viaje->conductor->update(['disponible' => true]);
                }
                $viaje->estado = 'Cancelado por el pasajero';
            } elseif ($rol === 'Conductor') {
                if ($viaje->pasajero) {
                    $viaje->pasajero->increment('billetera', $viaje->saldo_bloqueado); // Reembolso completo
                }
                $viaje->estado = 'Cancelado por el conductor';
                $viaje->conductor->update(['disponible' => true]);
            }
        } else {
            // Si el pago es en efectivo, solo cancelamos el viaje
            if ($rol === 'Conductor') {
                $viaje->conductor->update(['disponible' => true]);
            }
            $viaje->estado = 'Cancelado por el pasajero';
        }

        // Resetear saldo bloqueado y guardar cambios
        $viaje->saldo_bloqueado = 0;
        $viaje->save();

        return $viaje; // Devuelve el viaje actualizado
    }
}
