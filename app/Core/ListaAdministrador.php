<?php

namespace App\Core;

use App\Core\Services\AdministradorService;
use App\Core\Services\PersonaService;

use App\Core\Validations\AdministradorValidation;
use Illuminate\Validation\ValidationException;

class ListaAdministrador
{
    private $service;


    public function __construct()
    {
        $this->service = new AdministradorService();


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
     *
     */
    public function add(Persona $persona, Administrador $administrador)
    {
        return $this->service->add($persona, $administrador);
    }

    /**
     * Edita un administrador existente.
     *
     *
     */
    public function edit($id, Persona $persona, Administrador $administrador)
    {
       return $this->service->edit($id, $persona, $administrador);
    }

    /**
     * Inicia sesión como administrador.
     *

     */


    /**
     * Cierra la sesión del administrador.
     */

}
