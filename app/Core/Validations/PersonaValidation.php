<?php

namespace App\Core\Validations;

use Illuminate\Support\Facades\Validator;

class PersonaValidation
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
            'billetera' => 'min:0',
             'foto' => 'nullable|image|max:2048', // Validar campo foto
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
            'billetera' => 'numeric|min:0',
            'foto' => 'nullable|image|max:2048', // Validar campo foto
        ]);
    }

    public static function validateLogin(array $data)
    {
        return Validator::make($data, [
            'usuario' => 'required|string|max:50',
            'password' => 'required|string|min:3|max:100',
        ]);
    }

    public static function validateRecargarBilletera(array $data)
    {
        return Validator::make($data, [
            'monto' => 'required|numeric|min:0',
        ]);
    }
}
