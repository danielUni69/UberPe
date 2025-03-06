<?php

namespace App\Core;

use App\Core\Services\PasajeroService;
use Illuminate\Validation\ValidationException;

class ListaPasajero
{
    private $servicePasajero;
    public function __construct()
    {   
        $this->servicePasajero = new PasajeroService;
    }

    public function solicitarServicio($origen, $destino, $metodo_pago, $tarifa){
        return $this->servicePasajero->solicitarServicio($origen, $destino, $metodo_pago, $tarifa);
    }
}