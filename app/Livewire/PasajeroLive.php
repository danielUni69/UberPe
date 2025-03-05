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
    public $tarifa;
    public $metodo;
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
        $this->tarifa = 0;
        $this->metodo = 'No hay metodo de pago';
    }


    public function estado_viaje(){
        $this->listaPersona = new ListaPersona;
        $this->estado =  $this->listaPersona->estado_viaje();
        if ($this->estado == 'En curso') {
            $this->verTarifa();
            $this->verMetodo();   
        } else {
            $this->tarifa = 0;
            $this->metodo = 'No hay metodo de pago';
        }
    }

    public function verTarifa(){
        $this->listaPersona = new ListaPersona;
        $this->tarifa = $this->listaPersona->verTarifa();
        
    }
    public function verMetodo(){
        $this->listaPersona = new ListaPersona;
        $this->metodo = $this->listaPersona->verMetodo();
    }

    public function render()
    {
        return view('livewire.pasajero-live');
    }
}
