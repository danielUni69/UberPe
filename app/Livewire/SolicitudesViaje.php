<?php

namespace App\Livewire;

use App\Core\ListaConductor;
use Livewire\Component;
use App\Models\ViajeModel;
use App\Models\PersonaModel;

class SolicitudesViaje extends Component
{
    public $solicitudes;
    public $solicitudSeleccionada;

    private $listaConductor;

    protected $listeners = ['solicitudRecibida' => 'actualizarSolicitudes'];

    public function mount()
    {
        $this->actualizarSolicitudes();
    }

    public function actualizarSolicitudes()
    {
        $this->listaConductor = new ListaConductor;
        $this->solicitudes = $this->listaConductor->viajesPendientes();
    }

    public function seleccionarSolicitud($id)
    {
        $this->solicitudSeleccionada = ViajeModel::with('pasajero')->find($id);
        
        $this->dispatch('solicitudSeleccionada', solicitud: $this->solicitudSeleccionada); 
    }

    public function aceptarViaje($id)
    {
        $this->listaConductor = new ListaConductor;
        $viaje = $this->listaConductor->aceptarViaje($id);
        $this->dispatch('viajeAceptado', viaje: $viaje); 
        $this->actualizarSolicitudes();
    }

    public function render()
    {
        return view('livewire.solicitudes-viaje');
    }
}