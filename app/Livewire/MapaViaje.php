<?php

namespace App\Livewire;

use App\Core\ListaPersona;
use App\Core\Pasajero;
use App\Core\Services\PasajeroService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MapaViaje extends Component
{   
    public $origen;
    public $destino;
    public $metodo_pago = 'Billetera'; 
    public $tarifa;
    public $viaje;

    
    protected $rules = [
        'origen' => 'required',
        'destino' => 'required',
        'metodo_pago' => 'required|in:Billetera,Efectivo',
        'tarifa' => 'required|numeric|min:0',
    ];

    public function initMap()
    {
        $this->dispatch('init-map'); // Emitir un evento para inicializar el mapa
    }
    
    // Método para actualizar el origen
    public function setOrigen($origen)
    {
        $this->origen = $origen;
    }

    // Método para actualizar el destino
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    // Método para limpiar el origen
    public function clearOrigen()
    {
        $this->origen = '';
    }

    // Método para limpiar el destino
    public function clearDestino()
    {
        $this->destino = '';
    }

    
    public function solicitarServicio()
    {
        $this->validate();

        try {
            // Llamar a la función del servicio
            $personaService = app(PasajeroService::class);
            $this->viaje = $personaService->solicitarServicio(
                $this->origen,
                $this->destino,
                $this->metodo_pago,
                $this->tarifa
            );

            // Emitir un evento para actualizar el mapa
            $this->emit('viajeSolicitado', $this->viaje);
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    public function cancelarViaje()
    {
        try {
            // Llamar a la función del servicio
            $personaService = app(ListaPersona::class);
            $this->viaje = $personaService->cancelarViaje();

            // Limpiar el viaje actual
            $this->viaje = null;
            $this->emit('viajeCancelado'); // Emitir un evento para actualizar el mapa
        } catch (\Exception $e) {
            $this->addError('error', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.mapa-viaje');
    }
}
