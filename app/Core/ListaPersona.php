<?php

namespace App\Core;

use App\Core\Services\PersonaService;

class ListaPersona
{
    private $service;

    public function __construct()
    {
        $this->service = new PersonaService;
    }

    public function list()
    {
        return $this->service->getPersonas();
    }

    public function add(Persona $persona)
    {
        $this->service->add($persona);
    }

    public function edit(Persona $persona)
    {
        $this->service->edit($persona);
    }

    public function iniciarSesion($usuario, $password)
    {
        return $this->service->iniciarSesion($usuario, $password);
    }

    public function verBilletera($id)
    {
        return $this->service->verBilletera($id);
    }

    public function recargarBilletera($id, $monto)
    {
        return $this->service->recargarBilletera($id, $monto);
    }
}
