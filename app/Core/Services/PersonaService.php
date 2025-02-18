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
        $personaModel->ci = $persona->getCi();
        $personaModel->nombres = $persona->getNombres();
        $personaModel->apellidos = $persona->getApellidos();
        $personaModel->telefono = $persona->getTelefono();
        $personaModel->email = $persona->getEmail();
        $personaModel->usuario = $persona->getUsuario();
        $personaModel->password = $persona->getPassword();
        $personaModel->rol = $persona->getRol();
        $personaModel->billetera = $persona->getBilletera();
        $personaModel->deuda = $persona->getDeuda();
        $personaModel->save();
    }

    public function edit(Persona $persona, $id)
    {
        $personaModel = PersonaModel::find($id);
        if ($personaModel) {
            $personaModel->ci = $persona->getCi();
            $personaModel->nombres = $persona->getNombres();
            $personaModel->apellidos = $persona->getApellidos();
            $personaModel->telefono = $persona->getTelefono();
            $personaModel->email = $persona->getEmail();
            $personaModel->usuario = $persona->getUsuario();
            $personaModel->password = $persona->getPassword();
            $personaModel->rol = $persona->getRol();
            $personaModel->billetera = $persona->getBilletera();
            $personaModel->deuda = $persona->getDeuda();
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
