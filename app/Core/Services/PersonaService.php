<?php

namespace App\Core\Services;

use App\Core\Persona;
use App\Core\Reclamo;
use App\Core\Viaje as CoreViaje;
use App\Models\PersonaModel;
use App\Models\ReclamoModel;
use App\Models\Viaje;
use App\Models\ViajeModel;
use Illuminate\Support\Facades\Log;

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
    public function getPersonaByUsuario($usuario)
    {
    return PersonaModel::where('usuario', $usuario)->first();
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

    public function estado_viaje(){
        $user = Auth::user();
        if($user->rol == "Pasajero"){
            $viaje = ViajeModel::where('pasajero_id', $user->id_persona)
            ->orderBy('created_at', 'desc')
            ->first();
        }else {
            $viaje = ViajeModel::where('conductor_id', $user->conductor->id_conductor)
            ->orderBy('created_at', 'desc')->first();
        }
        if ($viaje) {
            if ($viaje->estado === 'Cancelado por el conductor' || $viaje->estado === 'Cancelado por el pasajero' || $viaje->estado === 'Completado' || $viaje->estado === 'Completado sin pagar') {
                if ($user->rol === 'Pasajero') {
                    return'Viaje finalizado';
                }
                return 'No hay viajes'; 
            }    
            return $viaje->estado;
        }else {
            return 'No hay viajes';
        }
    }

    public function cancelarViaje()
{
    try {
        $user = Auth::user();
        $rol = $user->rol;

        $query = ViajeModel::query();
    
    if ($rol === 'Pasajero') {
        $query->where('pasajero_id', $user->id_persona);
    } elseif ($rol === 'Conductor') {
        $query->where('conductor_id', $user->conductor->id_conductor);
    }
    
    $viaje = $query->whereIn('estado', ['En curso', 'Pendiente'])
        ->first();

    if (! $viaje) {
        throw new \Exception('No tienes viajes activos para cancelar.', 400);
    }
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
                $viaje->estado = 'Cancelado por el conductor';
                $viaje->conductor->update(['disponible' => true]);
            } else {
                $viaje->estado = 'Cancelado por el pasajero';
            }
            
        }

        
        $viaje->saldo_bloqueado = 0;
        $viaje->save();

        return $viaje; 
    } catch (\Exception $e) {
        return response()->json(['error' => 'cagaste. ' . $e->getMessage(). $user->nombres], 500);
    }
}

    public function obtenerUltimoViaje(){
        $user = Auth::user();
        if($user->rol == "Pasajero"){
            $viaje = ViajeModel::where('pasajero_id', $user->id_persona)
            ->orderBy('created_at', 'desc')
            ->first();
        } else {
            $viaje = ViajeModel::where('conductor_id', $user->conductor->id_conductor)
            ->orderBy('created_at', 'desc')
            ->first();
        }
        if (!$viaje){
            return null;
        }
        return $viaje;
    }
    public function verTarifa(){
        $viaje = $this->obtenerUltimoViaje();
        if ($viaje) {
            return $viaje->tarifa;
        }else {
            return 'No hay viajes';
        }
    }
    public function verMetodo(){
        $viaje = $this->obtenerUltimoViaje();
        if ($viaje) {
            return $viaje->metodo;
        }else {
            return 'No hay viajes';
        }
    }

    public function AddReclamo(Reclamo $reclamo){
        $persona = Auth::user();
        $viaje = $this->obtenerUltimoViaje();
    
        $newReclamo = new ReclamoModel;
        $newReclamo->persona_id = $persona->id_persona;
        $newReclamo->viaje_id = $viaje->id_viaje;
        $newReclamo->motivo = $reclamo->getMotivo();
        $newReclamo->fecha = $reclamo->getFecha();
        $newReclamo->save();
        
        return $newReclamo;
    }
    public function reclamoNopago(Reclamo $reclamo){
        $persona = Auth::user();
        $newReclamo = $this->AddReclamo($reclamo);
        $viaje = $this->obtenerUltimoViaje();
        $viaje->estado = 'Completado sin pagar';
        $viaje->save();
        if ($persona->rol === 'Conductor'){
            $persona->conductor->disponible = true;
            $persona->conductor->save();
        }
        $viaje->persona->deuda = $viaje->tarifa;
        $viaje->persona->save();
        return $newReclamo;
    }

}
