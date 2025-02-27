<?php

namespace App\Core\Services;

use App\Models\VehiculoModel;
use Illuminate\Support\Facades\Auth;


class VehiculoService
{
    public function getVehiculo()
    {
        $conductor =  Auth::user()->conductor;
        return VehiculoModel::where('conductor_id', $conductor->id_conductor)->get();
    }
}
