<?php

namespace App\Core\Services;

use App\Core\Persona;
use App\Models\PersonaModel;

class PersonaService
{
    public function getPersonas()
    {
        return PersonaModel::all();
    }

    public function add(Persona $persona)
    {
        $personaModel = new PersonaModel;
        $personaModel->ci = $persona->ci;
        $personaModel->nombres = $persona->nombres;
        $personaModel->apellidos = $persona->apellidos;
        $personaModel->telefono = $persona->telefono;
        $personaModel->email = $persona->email;
        $personaModel->usuario = $persona->usuario;
        $personaModel->password = $persona->password;
        $personaModel->rol = $persona->rol;
        $personaModel->billetera = $persona->billetera;
        $personaModel->deuda = $persona->deuda;
        $personaModel->save();
    }

    public function edit(Persona $persona)
    {
        $personaModel = PersonaModel::find($persona->id);
        if ($personaModel) {
            $personaModel->ci = $persona->ci;
            $personaModel->nombres = $persona->nombres;
            $personaModel->apellidos = $persona->apellidos;
            $personaModel->telefono = $persona->telefono;
            $personaModel->email = $persona->email;
            $personaModel->usuario = $persona->usuario;
            $personaModel->password = $persona->password;
            $personaModel->rol = $persona->rol;
            $personaModel->billetera = $persona->billetera;
            $personaModel->deuda = $persona->deuda;
            $personaModel->save();
        }
    }

    public function iniciarSesion($usuario, $password)
    {
        $personaModel = PersonaModel::where('usuario', $usuario)->where('password', $password)->first();
        if ($personaModel) {
            return $personaModel->convertToPersona();
        }

        return null;
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
}
