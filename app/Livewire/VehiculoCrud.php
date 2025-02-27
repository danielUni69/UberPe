<?php

namespace App\Livewire;

use App\Models\ConductorModel;
use App\Models\VehiculoModel;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Core\ListaConductor;
class VehiculoCrud extends Component
{
    use WithFileUploads;

    

    public $vehiculos;
    public $conductores;

    
    public $isEdit = false;

    // Inicializar el componente
    public function mount(ListaConductor $listaConductor)
    {
        $this->vehiculos = VehiculoModel::all();
        $this->conductores = ConductorModel::all();
    }

    public function edit($id)
    {
        $vehiculo = VehiculoModel::find($id);
        $this->vehiculo_id = $vehiculo->id_vehiculo;
        $this->conductor_id = $vehiculo->conductor_id;
        $this->marca = $vehiculo->marca;
        $this->modelo = $vehiculo->modelo;
        $this->placa = $vehiculo->placa;
        $this->color = $vehiculo->color;
        $this->foto_temp = $vehiculo->foto;
        $this->isEdit = true;
    }

    // Eliminar un vehículo
    public function delete($id)
    {
        VehiculoModel::find($id)->delete();
        $this->vehiculos = VehiculoModel::all();
    }

    // Reiniciar el formulario
    public function resetForm()
    {
        $this->reset(['vehiculo_id', 'conductor_id', 'marca', 'modelo', 'placa', 'color', 'foto', 'foto_temp', 'isEdit']);
    }

    // Renderizar la vista
    public function render()
    {
        return view('livewire.vehiculo-crud');
    }
}
