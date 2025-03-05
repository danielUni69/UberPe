<?php
namespace App\Livewire;

use App\Core\ListaPersona;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\On;
class PasajeroLive extends Component
{
    public $estado;
    private $listaPersona;
    
    /*#[On('viajeAceptado')]
    public function viajeAceptado($viaje = null)
    {
        LivewireAlert::title('Item Saved')
        ->text('The item has been successfully saved to the database.')
        ->success()
        ->show();
        $this->estado_viaje();
    }*/
    public function mount()
    {
        $this->listaPersona = new ListaPersona;
        $this->estado =  $this->listaPersona->estado_viaje();
        if (!$this->estado){
            $this->estado = 'No hay viajes';
        } 
    }

    public function estado_viaje(){
        $this->listaPersona = new ListaPersona;
        $this->estado =  $this->listaPersona->estado_viaje();
    }

    public function render()
    {
        return view('livewire.pasajero-live');
    }
}
