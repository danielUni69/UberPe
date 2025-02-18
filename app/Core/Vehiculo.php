<?php

namespace App\Core;

class Vehiculo
{
    private $conductor;
    private $marca;
    private $modelo;
    private $placa;
    private $color;

    public function __construct(Conductor $conductor, $marca, $modelo, $placa, $color)
    {
        $this->conductor = $conductor;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->placa = $placa;
        $this->color = $color;
    }

    public function getConductor() { return $this->conductor; }
    public function getMarca() { return $this->marca; }
    public function getModelo() { return $this->modelo; }
    public function getPlaca() { return $this->placa; }
    public function getColor() { return $this->color; }

    public function setConductor(Conductor $conductor) { $this->conductor = $conductor; }
    public function setMarca($marca) { $this->marca = $marca; }
    public function setModelo($modelo) { $this->modelo = $modelo; }
    public function setPlaca($placa) { $this->placa = $placa; }
    public function setColor($color) { $this->color = $color; }
}
