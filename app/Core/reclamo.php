<?php

namespace App\Core;

class Reclamo
{
    private $persona_id;
    private $viaje_id;
    private $motivo;
    private $fecha;

    public function __construct($motivo, $fecha, $persona_id = null, $viaje_id = null)
    {
        $this->persona_id = $persona_id;
        $this->viaje_id = $viaje_id;
        $this->motivo = $motivo;
        $this->fecha = $fecha;
    }

    public function getPersonaId()
    {
        return $this->persona_id;
    }

    public function getViajeId()
    {
        return $this->viaje_id;
    }

    public function getMotivo()
    {
        return $this->motivo;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function setPersonaId($persona_id)
    {
        $this->persona_id = $persona_id;
    }

    public function setViajeId($viaje_id)
    {
        $this->viaje_id = $viaje_id;
    }

    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
}