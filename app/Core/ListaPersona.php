<?php

namespace App\Core;

use App\Core\Services\PersonaService;
use App\Core\Validations\PersonaValidation;
use Illuminate\Validation\ValidationException;

class ListaPersona
{
    private $service;

    public function __construct()
    {

        $this->service = new PersonaService;
    }

    public function list()
    {
        return $this->service->getPersonas();
    }

    public function add(Persona $persona)
    {
        $data = [
            'ci' => $persona->getCi(),
            'nombres' => $persona->getNombres(),
            'apellidos' => $persona->getApellidos(),
            'telefono' => $persona->getTelefono(),
            'email' => $persona->getEmail(),
            'usuario' => $persona->getUsuario(),
            'password' => $persona->getPassword(),
            'rol' => $persona->getRol(),
            'billetera' => $persona->getBilletera(),
        ];

        $validator = PersonaValidation::validateAdd($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->service->add($persona);
    }

    public function edit(Persona $persona, $id)
    {

        $this->service->editPersona($persona, $id);
    }

    /**
     * Inicia sesión.
     *
     * @param  string  $usuario
     * @param  string  $password
     * @return bool
     */
    public function iniciarSesion($usuario, $password)
    {

        $data = [
            'usuario' => $usuario,
            'password' => $password,
        ];
        $validator = PersonaValidation::validateLogin($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->service->iniciarSesion($usuario, $password);
    }

    /**
     * Cierra la sesión.
     */
    public function cerrarSesion()
    {
        $this->service->cerrarSesion();
    }

    public function verBilletera($id)
    {
        return $this->service->verBilletera($id);
    }

    public function recargarBilletera($id, $monto)
    {

        $data = [
            'monto' => $monto,
        ];

        $validator = PersonaValidation::validateRecargarBilletera($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->service->recargarBilletera($id, $monto);
    }

    public function cancelarViaje()
    {
        return $this->service->cancelarViaje();
    }

    /**
     * Obtiene una persona por su ID.
     *
     * @param  int  $id
     * @return PersonaModel|null
     */
    public function getPersona($id)
    {
        return $this->service->getPersona($id);
    }
}
