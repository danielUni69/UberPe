<?php

namespace App\Livewire;

use App\Core\ListaConductor;
use App\Core\ListaPersona;
use App\Core\Reclamo;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
class FinalizarViaje extends Component
{   
    private $listaConductor;
    private $listaPersona;

    public $persona_id;
    public $viaje_id;
    public $motivo;

    public $showModal = false;

    public function finalizarViaje(){
        $this->listaConductor = new ListaConductor;
        $viaje = $this->listaConductor->finalizarViaje();
        if($viaje->metodo === 'Efectivo'){
            $this->dispatch('holaModal');
            $this->showModal = true;
            /*LivewireAlert::title('Confirmar pago')
            ->text('Confirmar pago en efectivo')
            ->question()
            ->asConfirm()
            ->withConfirmButton('Confirmar')
            ->withDenyButton('Reportar que no se pago')
            ->onConfirm('confirmarPago')
            ->onDeny('reportarNopago')
            ->show();*/
        } else {
            $this->dispatch('viajeFinalizado');
            LivewireAlert::title('Success') 
            ->text('Viaje finalizado con exito.')
            ->success()
            ->timer(3000) 
            ->show();
        }
    }
    public function confirmarPago(){
        $this->listaConductor = new ListaConductor;
        $this->listaConductor->confirmarPago();
        $this->dispatch('viajeFinalizado');
        $this->showModal = false;
        LivewireAlert::title('Success')
        ->text('Pago confirmado!.')
        ->success()
        ->timer(3000) 
        ->show();
    }
    public function reportarNopago(){
        $this->listaPersona = new ListaPersona;
        $reclamo = new Reclamo(
            'El pasajero no pago en viaje',
            now()
        );
        $this->listaPersona->reclamoNoPago($reclamo);
        $this->dispatch('viajeFinalizado');
        $this->showModal = false;
        LivewireAlert::title('Success')->success()->timer(3000)->show();
    }

    public function render()
    {
        return view('livewire.finalizar-viaje');
    }
}
