<?php

namespace App\Http\Controllers;

use App\Core\Conductor;
use App\Core\ListaConductor;
use App\Core\Persona;
use App\Core\Vehiculo;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    private ListaConductor $listaConductor;

    public function __construct()
    {
        $this->listaConductor = new ListaConductor;
    }

    public function showRegistroForm()
    {
        return view('conductor.registro');
    }

    public function store(Request $request)
    {

        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            bcrypt($request->input('password')), // Encriptar contraseña
            'Conductor',
            $request->input('billetera')
        );
        $conductor = new Conductor(
            $request->input('licencia'),
            $request->input('disponible'),
        );
        $vehiculo = new Vehiculo(
            $request->input('marca'),
            $request->input('modelo'),
            $request->input('placa'),
            $request->input('color'),
        );
        $this->listaConductor->add($persona, $conductor, $vehiculo);

        return redirect()->route('home')->with('success', 'Usuario creado exitosamente.');
    }

    public function showEditConductorForm($id = null)
    {
        if ($id == null) {
            $id = auth()->user()->id_persona;
            $conductor = $this->listaConductor->getConductor($id);
        } else {
            $conductor = $this->listaConductor->getConductor($id);
        }

        return view('conductor.editar', compact('conductor'));
    }

    public function update(Request $request)
    {

        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            bcrypt($request->input('password')), // Encriptar contraseña
            'Conductor',
            $request->input('billetera')
        );
        $conductor = new Conductor(
            $request->input('licencia'),
            $request->input('disponible'),
        );
        $this->listaConductor->add($persona, $conductor, $vehiculo);

        return redirect()->route('home')->with('success', 'Usuario creado exitosamente.');
    }
}
