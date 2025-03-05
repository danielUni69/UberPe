<?php

namespace App\Livewire;

use Livewire\Component;
use App\Core\ListaConductor;
use Livewire\Attributes\On;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
class CambiarEstado extends Component
{
    public $disponible;
    private $listaConductorService;

    
    public function mount(ListaConductor $listaConductorService)
    {
        $this->listaConductorService = $listaConductorService;
        
        $this->disponible = $this->listaConductorService->verEstado();
    }

    #[On('viajeAceptado')]
    #[On('viajeCancelado')]
    public function verEstado(){
        $this->listaConductorService = new ListaConductor;
        $this->disponible = $this->listaConductorService->verEstado();
        $this->render();
    }

    public function cambiarEstado()
    {
        $this->listaConductorService = new ListaConductor;
        $this->disponible = $this->listaConductorService->cambiarEstado();
    }

    public function render()
    {
        return view('livewire.cambiar-estado');
    }
}
