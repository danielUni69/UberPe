<?php

namespace App\Core\Services;

use App\Core\Vehiculo;
use App\Models\VehiculoModel;
use Illuminate\Support\Facades\Auth;


class VehiculoService
{
    public function getVehiculo()
    {
        $conductor =  Auth::user()->conductor;
        $vehiculo  = VehiculoModel::where('conductor_id', $conductor->id_conductor)->first();
        return $vehiculo->convertToVehiculo();
    }
    public function edit(Vehiculo $vehiculo){
        $conductor =  Auth::user()->conductor;
        $vehiculoModel = VehiculoModel::where('conductor_id', $conductor->id_conductor)->first();
        $vehiculoModel->marca = $vehiculo->getMarca();
        $vehiculoModel->modelo = $vehiculo->getModelo();
        $vehiculoModel->placa = $vehiculo->getPlaca();
        $vehiculoModel->color = $vehiculo->getColor();
        $vehiculoModel->foto = $vehiculo->getFoto();
        $vehiculoModel->save();
    }
}
