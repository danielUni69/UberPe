<?php

namespace App\Core\Validations;

use Illuminate\Support\Facades\Validator;

class ConductorValidation
{
    public static function validateAdd(array $data)
    {
        return Validator::make($data, [
            'ci' => 'required|unique:persona,ci|max:20',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'telefono' => 'required|max:15',
            'email' => 'required|email|unique:persona,email|max:100',
            'usuario' => 'required|unique:persona,usuario|max:50',
            'password' => 'required|max:100',
            'licencia' => 'required|max:20',
            'disponible' => 'required|boolean',
            'marca' => 'required|max:50',
            'placa' => 'required|max:10',
            'modelo' => 'required|max:50',
            'color' => 'required|max:50',
        ]);
    }

    public static function validateEdit(array $data, $id)
    {
        return Validator::make($data, [
            'ci' => 'required|unique:persona,ci,' . $id . ',id_persona|max:20',
            'nombres' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'telefono' => 'required|max:15',
            'email' => 'required|email|unique:persona,email,' . $id . ',id_persona|max:100',
            'usuario' => 'required|unique:persona,usuario,' . $id . ',id_persona|max:50',
            'password' => 'nullable|max:100',
            'licencia' => 'required|max:20',
            'disponible' => 'required|boolean',
        ]);
    }

    public static function validateViajePendiente(array $data)
    {
        return Validator::make($data, [
            'conductor_id' => 'required|exists:conductor,id_conductor',
            'estado' => 'required|in:Pendiente,En curso,Completado,Cancelado',
        ]);
    }

    public static function validateAceptarViaje(array $data)
    {
        return Validator::make($data, [
            'viaje_id' => 'required|exists:viaje,id_viaje',
            'conductor_id' => 'required|exists:conductor,id_conductor',
        ]);
    }

    public static function validateFinalizarViaje(array $data)
    {
        return Validator::make($data, [
            'viaje_id' => 'required|exists:viaje,id_viaje',
            'conductor_id' => 'required|exists:conductor,id_conductor',
        ]);
    }

    public static function validateConfirmarPago(array $data)
    {
        return Validator::make($data, [
            'viaje_id' => 'required|exists:viaje,id_viaje',
            'conductor_id' => 'required|exists:conductor,id_conductor',
        ]);
    }
    public static function validateVerHistorial(array $data)
    {
    return Validator::make($data, [
        'conductor_id' => 'required|exists:conductor,id_conductor', // Valida que el conductor exista
    ]);
    }
}
