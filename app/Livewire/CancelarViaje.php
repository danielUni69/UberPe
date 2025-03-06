<?php

namespace App\Livewire;

use App\Core\ListaConductor;
use App\Core\ListaPersona;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
class CancelarViaje extends Component
{
    private $listaPersona;

    public function notyCancelar(){
        LivewireAlert::title('Estas seguro?')
        ->text('Estas seguro de cancelar el viaje?')
        ->asConfirm()
        ->onConfirm('cancelarViaje')
        ->show();
    }
    public function cancelarViaje(){
        $this->listaPersona = new ListaPersona;
        $this->listaPersona->cancelarViaje();
        $this->dispatch('viajeCancelado');
    }

    public function render()
    {
        return view('livewire.cancelar-viaje');
    }
}
