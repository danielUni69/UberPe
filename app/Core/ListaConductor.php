<?php

namespace App\Core;

use App\Core\Services\ConductorService;

class ListaConductor
{
    private $service;

    public function __construct()
    {

        $this->service = new ConductorService;
    }

    public function add(Persona $persona, Conductor $conductor, Vehiculo $vehiculo)
    {

        return $this->service->add($persona, $conductor, $vehiculo);
    }

    public function getConductor($id)
    {
        return $this->service->getConductor($id);
    }
    public function edit($id, $persona, $conductor){
        return $this->service->edit($id, $persona, $conductor);
    }
    public  function cambiarEstado() {
        return  $this->service->cambiarEstado();
    }
    public function verEstado(){
        return $this->service->verEstado();
    }
    public function viajesPendientes(){
        return $this->service->viajesPendientes();
    }
    public function aceptarViaje($id_viaje){
        return $this->service->aceptarViaje($id_viaje);
    }
    public function finalizarViaje(){
        return $this->service->finalizarViaje();
    }
    public function confirmarPago(){
        return $this->service->confirmarPago();
    }

}
