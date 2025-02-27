<?php

namespace App\Livewire;

use Livewire\Component;
use App\Core\ListaConductor;

class CambiarEstado extends Component
{
    public $disponible;
    private $listaConductorService;

    // Inyectar el servicio en el método mount()
    public function mount(ListaConductor $listaConductorService)
    {
        $this->listaConductorService = $listaConductorService;
        
        $this->disponible = $this->listaConductorService->cambiarEstado();
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
