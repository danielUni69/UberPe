<?php

namespace App\Core\Services;

use App\Models\ViajeModel;
use Illuminate\Support\Facades\Auth;


class ViajeService
{
    public function getViajes()
    {
        return ViajeModel::all();
    }
    
    public function getViajesPasajero()
    {
        $pasajero =  Auth::user();
        return ViajeModel::where('persona_id', $pasajero->id_persona)->get();
    }
    public function getViajesConductor()
    {
        $conductor =  Auth::user()->conductor;
        return ViajeModel::where('conductor_id', $conductor->id_conductor)->get();
    }
}
