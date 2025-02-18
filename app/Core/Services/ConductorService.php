<?php

namespace App\Core\Services;

use App\Core\Conductor;
use App\Models\ConductorModel;
use App\Models\PersonaModel;

class ConductorService
{
    public function getConductores()
    {
        return ConductorModel::with('persona')->get();
    }

    public function add(Conductor $conductor)
    {
        // Primero, agregamos la persona asociada al conductor
        $personaModel = new PersonaModel;
        $personaModel->ci = $conductor->getCi();
        $personaModel->nombres = $conductor->getNombres();
        $personaModel->apellidos = $conductor->getApellidos();
        $personaModel->telefono = $conductor->getTelefono();
        $personaModel->email = $conductor->getEmail();
        $personaModel->usuario = $conductor->getUsuario();
        $personaModel->password = $conductor->getPassword();
        $personaModel->rol = $conductor->getRol();
        $personaModel->billetera = $conductor->getBilletera();
        $personaModel->save();

        // Luego, agregamos el conductor
        $conductorModel = new ConductorModel;
        $conductorModel->persona_id = $personaModel->id_persona;
        $conductorModel->licencia = $conductor->getLicencia();
        $conductorModel->disponible = $conductor->getDisponible();
        $conductorModel->save();
    }

    public function edit(Conductor $conductor, $id)
    {
        $conductorModel = ConductorModel::find($id);
        if ($conductorModel) {
            $personaModel = PersonaModel::find($conductorModel->persona_id);
            if ($personaModel) {
                $personaModel->ci = $conductor->getCi();
                $personaModel->nombres = $conductor->getNombres();
                $personaModel->apellidos = $conductor->getApellidos();
                $personaModel->telefono = $conductor->getTelefono();
                $personaModel->email = $conductor->getEmail();
                $personaModel->usuario = $conductor->getUsuario();
                $personaModel->password = $conductor->getPassword();
                $personaModel->rol = $conductor->getRol();
                $personaModel->billetera = $conductor->getBilletera();
                $personaModel->save();
            }

            // Editamos el conductor
            $conductorModel->licencia = $conductor->getLicencia();
            $conductorModel->disponible = $conductor->getDisponible();
            $conductorModel->save();
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
}
