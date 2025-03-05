<?php

namespace App\Core\Validations;

use Illuminate\Support\Facades\Validator;

class PersonaValidation
{
    public static function validateAdd(array $data)
    {
        return Validator::make($data, [
            'ci' => 'required|unique:persona,ci|max:20',
            'nombres' => [
                'required',
                'max:100',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ\s]*$/', // Solo letras, empieza con mayúscula
            ],
            'apellidos' => [
                'required',
                'max:100',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ\s]*$/', // Solo letras, empieza con mayúscula
            ],
            'telefono' => 'required|max:15|regex:/^[0-9]+$/', // Solo números
            'email' => [
                'required',
                'email',
                'unique:persona,email',
                'max:100',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/', // Solo correos de @gmail.com
            ],
            'usuario' => 'required|unique:persona,usuario|max:50',
            'password' => 'required|max:100',
            'billetera' => 'nullable|numeric|min:0',
            'foto' => 'nullable|image|max:2048', // Validar campo foto
        ], [
            'nombres.regex' => 'El nombre debe contener solo letras y comenzar con mayúscula.',
            'apellidos.regex' => 'El apellido debe contener solo letras y comenzar con mayúscula.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'email.regex' => 'El correo electrónico debe ser de @gmail.com.',
        ]);
    }

    public static function validateEdit(array $data, $id)
    {
        return Validator::make($data, [
            'ci' => 'required|unique:persona,ci,' . $id . ',id_persona|max:20',
            'nombres' => [
                'required',
                'max:100',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ\s]*$/', // Solo letras, empieza con mayúscula
            ],
            'apellidos' => [
                'required',
                'max:100',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ\s]*$/', // Solo letras, empieza con mayúscula
            ],
            'telefono' => 'required|max:15|regex:/^[0-9]+$/', // Solo números
            'email' => [
                'required',
                'email',
                'unique:persona,email,' . $id . ',id_persona',
                'max:100',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/', // Solo correos de @gmail.com
            ],
            'usuario' => 'required|unique:persona,usuario,' . $id . ',id_persona|max:50',
            'foto' => 'nullable|image|max:2048', // Validar campo foto
        ], [
            'nombres.regex' => 'El nombre debe contener solo letras y comenzar con mayúscula.',
            'apellidos.regex' => 'El apellido debe contener solo letras y comenzar con mayúscula.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'email.regex' => 'El correo electrónico debe ser de @gmail.com.',
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
