<?php
namespace App\Http\Controllers;

use App\Core\ListaAdministrador;
use App\Core\ListaPersona;
use App\Core\Persona;
use App\Core\Conductor;
use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    private ListaAdministrador $listaAdministrador;
    private ListaPersona $listaPersona;

    public function __construct()
    {
        $this->listaAdministrador = new ListaAdministrador;
        $this->listaPersona = new ListaPersona;
    }

    public function index()
    {
        return view('administrador.index');
    }

    public function listarConductores()
    {
        // Obtener todos los conductores con sus vehículos desde el servicio
        $conductores = $this->listaAdministrador->listarConductores();
        return view('administrador.conductores', compact('conductores'));
    }

    public function listarPasajeros()
    {
        // Obtener todos los pasajeros desde el servicio
        $pasajeros = $this->listaAdministrador->listarPasajeros();
        return view('administrador.pasajeros', compact('pasajeros'));
    }

    public function showRegistroForm()
    {
        return view('administrador.registro');
    }
}
