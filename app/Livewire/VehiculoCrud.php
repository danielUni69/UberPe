<?php

namespace App\Livewire;

use App\Core\ListaVehiculo;
use App\Core\Vehiculo;
use App\Models\ConductorModel;
use App\Models\VehiculoModel;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehiculoCrud extends Component
{
    use WithFileUploads;

    protected $listaVehiculo;
    protected $vehiculo;


    public $vehiculos;
    
    public $marca;
    public $modelo;
    public $placa;
    public $color;
    public $foto;
    public $foto_temp;

    

    public $isEdit = true;

    public function mount()
    {
        $this->listaVehiculo = new ListaVehiculo;
        $this->vehiculo = $this->listaVehiculo->getVehiculo();  
        $this->marca = $this->vehiculo->getMarca();
        $this->modelo = $this->vehiculo->getModelo();
        $this->placa = $this->vehiculo->getPlaca();
        $this->color = $this->vehiculo->getColor();
        $this->foto_temp = $this->vehiculo->getFoto();
    }

    public function save($id = null)
    {
        $this->listaVehiculo = new ListaVehiculo;
        $vehiEdit = new Vehiculo(
            $this->marca,
            $this->modelo,
            $this->placa,
            $this->color,
            $this->foto_temp
        );    
        $this->listaVehiculo->editVehiculo($vehiEdit);
        return redirect()->route('home-conductor')->with('success', 'Vehiculo actualizado exitosamente.');
    }

    public function delete($id)
    {
        VehiculoModel::find($id)->delete();
        $this->vehiculos = VehiculoModel::all();
    }

    
    public function resetForm()
    {
        $this->reset(['vehiculo_id', 'conductor_id', 'marca', 'modelo', 'placa', 'color', 'foto', 'foto_temp', 'isEdit']);
    }

    public function render()
    {
        return view('livewire.vehiculo-crud');
    }
}
