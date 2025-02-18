<?php

namespace App\Core;

class Persona
{
    public $ci;

    public $nombres;

    public $apellidos;

    public $telefono;

    public $email;

    public $usuario;

    public $password;

    public $rol;

    public $billetera;

    public $deuda;

    public function __construct($ci, $nombres, $apellidos, $telefono, $email, $usuario, $password, $rol, $billetera)
    {
        $this->ci = $ci;
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->usuario = $usuario;
        $this->password = $password;
        $this->rol = $rol;
        $this->billetera = $billetera;
        $this->deuda = 0;
    }
}
