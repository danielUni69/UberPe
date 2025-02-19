<?php

namespace App\Core\Services;

// nombres Jose daniel Basilio Nina, Marbin Mamamani, Rodrigo
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
        $personaModel->password = Hash::make($persona->getPassword()); // Encriptar la contraseña
        $personaModel->rol = $persona->getRol();
        $personaModel->billetera = $persona->getBilletera();
        $personaModel->deuda = $persona->getDeuda();
        $personaModel->save();

        return $personaModel;
    }

    /**
     * Edita una persona existente.
     *
     * @param  int  $id
     * @return PersonaModel
     */
    public function editarPersona($id, Persona $persona)
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

            // Si se proporciona una nueva contraseña, se encripta
            if ($persona->getPassword()) {
                $personaModel->password = Hash::make($persona->getPassword());
            }

            $personaModel->rol = $persona->getRol();
            $personaModel->billetera = $persona->getBilletera();
            $personaModel->deuda = $persona->getDeuda();
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
            session(['rol' => $user->rol]); // Guardar el rol en sesión

            return response()->json([
                'mensaje' => 'Inicio de sesión exitoso',
                'usuario' => $user,
            ], 200);
        }

        return response()->json(['mensaje' => 'Credenciales incorrectas'], 401);
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cerrarSesion()
    {
        Auth::logout(); // Cierra la sesión del usuario

        session()->forget('rol'); // Opcional: eliminar el rol de la sesión

        return response()->json(['mensaje' => 'Sesión cerrada correctamente'], 200);
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
        return PersonaModel::find($id);
    }

    public function cancelarViaje()
    {
        $user = Auth::user();

        $rol = session('rol') ?? $user->rol; // Asegurar que siempre haya un rol válido

        if (! in_array($rol, ['Pasajero', 'Conductor'])) {
            return response()->json(['mensaje' => 'No tienes permisos para cancelar viajes.'], 403);
        }

        $viaje = ViajeModel::where(function ($query) use ($user, $rol) {
            if ($rol === 'pasajero') {
                $query->where('pasajero_id', $user->id_persona);
            } elseif ($rol === 'conductor') {
                $query->where('conductor_id', $user->id_persona);
            }
        })
            ->whereIn('estado', ['En curso', 'Pendiente'])
            ->first();

        if (! $viaje) {
            return response()->json(['mensaje' => 'No tienes viajes activos para cancelar.'], 400);
        }
        // dd($user);
        if (in_array($viaje->metodo, ['Billetera', 'Tarjeta'])) {
            if ($rol === 'Pasajero') {
                if ($viaje->conductor) {
                    $comision = $viaje->saldo_bloqueado * 0.1; // 10% de comisión para el conductor
                    $montoDevuelto = $viaje->saldo_bloqueado - $comision; // 90% que se devuelve al pasajero
                    // Incrementar el saldo del conductor con la comisión
                    $viaje->conductor->persona->billetera += $comision;
                    $viaje->conductor->persona->save();
                    // dd($viaje->conductor->persona->billetera);
                    // dd($viaje->conductor->persona->billetera);
                    // Devolver el 90% al saldo del pasajero
                    // dd($user->billetera);
                    $user->billetera += $montoDevuelto;
                    // dd($user->billetera);
                    $user->save();

                    // Marcar al conductor como disponible
                    $viaje->conductor->update(['disponible' => true]);
                } else {
                    $user = $viaje->saldo_bloqueado;
                }
                $viaje->estado = 'Cancelado por el pasajero';

            } elseif ($rol === 'conductor') {
                dd('Hola conductor');
                if ($viaje->pasajero) {
                    $viaje->pasajero->increment('billetera', $viaje->saldo_bloqueado); // Reembolso completo
                }
                $viaje->estado = 'Cancelado por el conductor';
                $viaje->conductor->update(['disponible' => true]);
            }
        } else {
            // Si el pago es en efectivo, solo cancelamos el viaje
            if ($rol === 'conductor') {
                $viaje->conductor->update(['disponible' => true]);
            }
            $viaje->estado = 'Cancelado por el pasajero';
        }
        // Resetear saldo bloqueado y guardar cambios
        $viaje->saldo_bloqueado = 0;
        $viaje->save();

        return response()->json([
            'mensaje' => 'Viaje cancelado exitosamente.',
            'estado' => $viaje->estado,
        ], 200);
    }
}
