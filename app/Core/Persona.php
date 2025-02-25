<?php

namespace App\Core;

class Persona
{
    private $ci;

    private $nombres;

    private $apellidos;

    private $telefono;

    private $email;

    private $usuario;

    private $password;

    private $rol;

    private $billetera;

    private $deuda;

    private $foto; // Añadir atributo foto

    public function __construct($ci, $nombres, $apellidos, $telefono, $email, $usuario, $rol, $billetera, $password = null, $foto = null)
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
        $this->foto = $foto; // Inicializar atributo foto
    }

    public function getCi()
    {
        return $this->ci;
    }

    public function getNombres()
    {
        return $this->nombres;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function getBilletera()
    {
        return $this->billetera;
    }

    public function getDeuda()
    {
        return $this->deuda;
    }

    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    public function setBilletera($billetera)
    {
        $this->billetera = $billetera;
    }

    public function setDeuda($deuda)
    {
        $this->deuda = $deuda;
    }
}
