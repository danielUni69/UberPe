<?php

namespace App\Livewire;

use App\Core\ListaConductor;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
class FinalizarViaje extends Component
{
    private $listaConductor;
    public function finalizarViaje(){
        $this->listaConductor = new ListaConductor;
        $this->listaConductor->finalizarViaje();
        LivewireAlert::title('Success')
        ->text('Viaje finalizado con exito.')
        ->success()
        ->timer(3000) 
        ->show();
        $this->dispatch('viajeFinalizado');
    }
    public function render()
    {
        return view('livewire.finalizar-viaje');
    }
}
