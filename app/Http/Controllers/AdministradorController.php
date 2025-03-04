<?php

namespace App\Http\Controllers;
use App\Core\Administrador;
use App\Core\ListaAdministrador;
use App\Core\ListaPersona;
use App\Core\Persona;
use App\Core\Validations\AdministradorValidation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
    public function showEditForm($id = null)
    {
       if ($id == null) {
            $id = auth()->user()->id_persona;
        }

        $administrador = $this->listaAdministrador->getAdministrador($id);

        return view('administrador.edit');
    }
    public function update(Request $request, $id = null)
    {
        if ($id == null) {
            $id = auth()->user()->id_persona;
        }

        // Validar los datos de entrada
        $validator = AdministradorValidation::validateEdit($request->all(), $id);

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Manejar la carga de la foto
        $fotoPath = Auth::user()->foto;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        // Crear una nueva persona con los datos actualizados
        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            'Administrador',
            null,
            null,
            $fotoPath
        );

        // Crear un nuevo administrador con los datos actualizados
        $administrador = new Administrador();

        // Actualizar el administrador y la persona en la lista
        $this->listaAdministrador->edit($id, $persona, $administrador);

        // Redirigir a la página de inicio con un mensaje de éxito
        return redirect()->route('administrador.index')->with('success', 'Administrador actualizado exitosamente.');
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
    public function showRegistroForm()
    {
        return view('administrador.registro');
    }
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validator = AdministradorValidation::validateAdd($request->all());

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->route('admin.registro')->withErrors($validator)->withInput();
        }

        // Manejar la carga de la foto
       /* $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }*/

        // Crear una nueva persona
        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            'Administrador',
            null,
            $request->input('password'),
           // $fotoPath
        );

        // Crear un nuevo administrador
        $administrador = new Administrador();


        // Añadir el administrador y la persona a la lista
        $this->listaAdministrador->add($persona, $administrador);

        // Redirigir a la página de inicio con un mensaje de éxito
        return redirect()->route('admin.home')->with('success', 'Administrador creado exitosamente.');
    }

}
