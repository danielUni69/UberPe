<?php

namespace App\Core;

use App\Core\Services\AdministradorService;
use App\Core\Services\PersonaService;

use App\Core\Validations\AdministradorValidation;
use Illuminate\Validation\ValidationException;

class ListaAdministrador
{
    private $service;
    private $servicePersona;

    public function __construct()
    {
        $this->service = new AdministradorService();
        $this->servicePersona = new PersonaService();

    }

    /**
     * Obtiene la lista de administradores.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list()
    {
        return $this->service->getPasajeros();

    }

    /**
     * Agrega un nuevo administrador.
     *
     * @param Persona $persona
     * @param Administrador $administrador
     * @throws ValidationException
     */
    public function add(Persona $persona, Administrador $administrador)
    {
        $data = [
            'ci' => $persona->getCi(),
            'nombres' => $persona->getNombres(),
            'apellidos' => $persona->getApellidos(),
            'telefono' => $persona->getTelefono(),
            'email' => $persona->getEmail(),
            'usuario' => $persona->getUsuario(),
            'password' => $persona->getPassword(),
        ];

        // Validar los datos de entrada
        $validator = AdministradorValidation::validateAdd($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Llamar al servicio para agregar el administrador
        $this->service->add($persona, $administrador);
    }

    /**
     * Edita un administrador existente.
     *
     * @param int $id
     * @param Persona $persona
     * @param Administrador $administrador
     * @throws ValidationException
     */
    public function edit($id, Persona $persona, Administrador $administrador)
    {
        $data = [
            'ci' => $persona->getCi(),
            'nombres' => $persona->getNombres(),
            'apellidos' => $persona->getApellidos(),
            'telefono' => $persona->getTelefono(),
            'email' => $persona->getEmail(),
            'usuario' => $persona->getUsuario(),
            'password' => $persona->getPassword(),
        ];

        // Validar los datos de entrada
        $validator = AdministradorValidation::validateEdit($data, $id);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Llamar al servicio para editar el administrador
        $this->service->edit($id, $persona);
    }

    /**
     * Inicia sesión como administrador.
     *
     * @param string $usuario
     * @param string $password
     * @return bool
     * @throws ValidationException
     */
    public function iniciarSesion($usuario, $password)
    {
        $data = [
            'usuario' => $usuario,
            'password' => $password,
        ];

        // Validar los datos de entrada
        $validator = AdministradorValidation::validateLogin($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Llamar al servicio para iniciar sesión
        return $this->servicePersona->iniciarSesion($usuario, $password);
    }

    /**
     * Cierra la sesión del administrador.
     */
    public function cerrarSesion()
    {
        $this->servicePersona->cerrarSesion();
    }

    /**
     * Obtiene un administrador por su ID.
     *
     * @param int $id
     * @return Persona|null
     */
    public function getAdministrador($id)
    {
        return $this->servicePersona->getPersona($id);
    }
}
