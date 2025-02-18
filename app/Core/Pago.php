<?php

namespace App\Core;

class Pago
{
    private $viaje;
    private $monto_total;
    private $comision;
    private $monto_conductor;
    private $fecha;

    public function __construct(Viaje $viaje, $monto_total, $comision, $monto_conductor, $fecha)
    {
        $this->viaje = $viaje;
        $this->monto_total = $monto_total;
        $this->comision = $comision;
        $this->monto_conductor = $monto_conductor;
        $this->fecha = $fecha;
    }

    public function getViaje() { return $this->viaje; }
    public function getMontoTotal() { return $this->monto_total; }
    public function getComision() { return $this->comision; }
    public function getMontoConductor() { return $this->monto_conductor; }
    public function getFecha() { return $this->fecha; }

    public function setMontoTotal($monto_total) { $this->monto_total = $monto_total; }
    public function setComision($comision) { $this->comision = $comision; }
    public function setMontoConductor($monto_conductor) { $this->monto_conductor = $monto_conductor; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
}
