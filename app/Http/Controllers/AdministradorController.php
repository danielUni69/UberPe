<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    public function index()
    {
        return view('administrador.index');
    }
    public function showEditForm()
    {
        return view('administrador.edit');
    }
    public function edit(){
        
    }
    public function showConductores()
    {
        return view('administrador.conductores');
    }
    public function showPasajeros()
    {
        return view('administrador.pasajeros');
    }   
}
