<?php

namespace App\Core;

class Conductor extends Persona
{
    private $licencia;
    private $disponible;
    private $calificaciones = [];

    public function __construct($ci, $nombres, $apellidos, $telefono, $email, $usuario, $password, $rol, $billetera, $licencia, $disponible = true)
    {
        parent::__construct($ci, $nombres, $apellidos, $telefono, $email, $usuario, $password, $rol, $billetera);
        $this->licencia = $licencia;
        $this->disponible = $disponible;
    }

    public function getLicencia() { return $this->licencia; }
    public function getDisponible() { return $this->disponible; }
    public function getCalificaciones() { return $this->calificaciones; }

    public function setLicencia($licencia) { $this->licencia = $licencia; }
    public function setDisponible($disponible) { $this->disponible = $disponible; }
    public function addCalificacion($calificacion) { $this->calificaciones[] = $calificacion; }
}
