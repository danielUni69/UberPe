<?php

namespace App\Core;

class Viaje
{
    private $pasajero;
    private $conductor;
    private $origen;
    private $destino;
    private $fecha;
    private $estado;
    private $tarifa;
    private $metodo_pago;
    private $saldo_bloqueado;
    private $descripcion;


    public function __construct($pasajero, $conductor, $origen, $destino, $fecha, $estado, $tarifa, $metodo_pago, $saldo_bloqueado, $descripcion)
    {
        $this->pasajero = $pasajero;
        $this->conductor = $conductor;
        $this->origen = $origen;
        $this->destino = $destino;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->tarifa = $tarifa;
        $this->metodo_pago = $metodo_pago;
        $this->saldo_bloqueado = $saldo_bloqueado;
        $this->descripcion = $descripcion;
    }

    public function getPasajero() { return $this->pasajero; }
    public function getConductor() { return $this->conductor; }
    public function getOrigen() { return $this->origen; }
    public function getDestino() { return $this->destino; }
    public function getFecha() { return $this->fecha; }
    public function getEstado() { return $this->estado; }
    public function getTarifa() { return $this->tarifa; }
    public function getMetodoPago() { return $this->metodo_pago; }
    public function getSaldoBloqueado() { return $this->saldo_bloqueado; }
    public function getDescripcion() { return $this->descripcion; }

    public function setEstado($estado) { $this->estado = $estado; }
    public function setTarifa($tarifa) { $this->tarifa = $tarifa; }
    public function setMetodoPago($metodo_pago) { $this->metodo_pago = $metodo_pago; }
    public function setSaldoBloqueado($saldo_bloqueado) { $this->saldo_bloqueado = $saldo_bloqueado; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
}
