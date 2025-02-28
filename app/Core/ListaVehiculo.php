<?php

namespace App\Core;

use App\Core\Services\VehiculoService;

class ListaVehiculo
{
    private $service;

    public function __construct()
    {
        $this->service = new VehiculoService;
    }
    public function getVehiculo()
    {
        return $this->service->getVehiculo();
    }
    public function editVehiculo(Vehiculo $vehiculo)
    {
        $this->service->edit($vehiculo);
    }
    
}
