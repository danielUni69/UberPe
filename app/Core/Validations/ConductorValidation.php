<?php

namespace App\Core\Validations;

use Illuminate\Support\Facades\Validator;

class ConductorValidation
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
               'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', // Permite cualquier dominio válido
              ],

            'usuario' => 'required|unique:persona,usuario|max:50',
            'password' => 'required|max:100',
            'licencia' => 'required|max:20',
            'disponible' => 'required|boolean',
            'marca' => 'required|max:50',
            'placa' => [
                'required',
                'max:10',
                'regex:/^[A-Z0-9]+$/', // Solo letras mayúsculas y números
            ],
            'modelo' => 'required|max:50',
            'color' => 'required|max:50',
            'foto' => 'required|image|max:3048', // Validar campo foto
        ], [
            'nombres.regex' => 'El nombre debe contener solo letras y comenzar con mayúscula.',
            'apellidos.regex' => 'El apellido debe contener solo letras y comenzar con mayúscula.',
            'telefono.regex' => 'El teléfono solo puede contener números.',
            'email.regex' => 'El correo electrónico debe ser de @gmail.com.',
            'placa.regex' => 'Solo letras mayúsculas y números.',
        ]);
    }

    public static function validateEdit(array $data, $id)
    {
        return Validator::make($data, [
            'ci' => 'required|unique:persona,ci,' . $id . ',id_persona|max:20',
            'nombres' => [
                'required',
                'max:100',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?$/', // Solo letras, empieza con mayúscula
            ],
            'apellidos' => [
                'required',
                'max:100',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?$/', // Solo letras, empieza con mayúscula
            ],
            'telefono' => 'required|max:15|regex:/^[0-9]+$/', // Solo números
            'email' => [
               'required',
               'email',
               'unique:persona,email,' . $id . ',id_persona',
               'max:100',
               'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', // Permite cualquier dominio válido


            ],
            'usuario' => 'required|unique:persona,usuario,' . $id . ',id_persona|max:50',
            'password' => 'nullable|max:100',
            'licencia' => 'required|max:20',
        ], [
            'nombres.regex' => 'El nombre debe contener solo letras y comenzar con mayúscula.',
            'apellidos.regex' => 'El apellido debe contener solo letras y comenzar con mayúscula.',
            'telefono.regex' => 'Solo se permiten números.',
            'email.regex' => 'El correo electrónico debe ser de @ ',
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
