<?php

namespace App\Http\Controllers;

use App\Core\Administrador;
use App\Core\ListaAdministrador;
use App\Core\ListaPersona;
use App\Core\Persona;
use App\Core\Validations\AdministradorValidation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    private ListaAdministrador $listaAdministrador;
    private ListaPersona $listaPersona;

    public function __construct()
    {
        // Inicializar las propiedades sin parámetros innecesarios
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
        return view('administrador.editar', compact('administrador'));
    }

    public function update(Request $request, $id = null)
    {
        if ($id == null) {
            $id = auth()->user()->id_persona;
        }

        // Validar los datos de entrada
        $validator = AdministradorValidation::validateEdit($request->all(), $id);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Obtener la persona actual
        $personaActual = $this->listaPersona->getPersona($id);

        // Manejar la carga de la foto
        $fotoPath = $personaActual->getFoto();
        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        // Crear una nueva instancia de Persona con los datos actualizados
        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            'Administrador',
            null,
            $personaActual->getPassword(), // Mantener la contraseña actual
            $fotoPath
        );

        // Crear una nueva instancia de Administrador
        $administrador = new Administrador();

        // Llamar al método edit de ListaAdministrador para actualizar los datos
        $this->listaAdministrador->edit($id, $persona, $administrador);

        return redirect()->route('admin.home')->with('success', 'Administrador actualizado exitosamente.');
    }

    public function showRegistroForm()
    {
        return view('administrador.registro');
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validator = AdministradorValidation::validateAdd($request->all());
        if ($validator->fails()) {
            return redirect()->route('admin.registro')->withErrors($validator)->withInput();
        }

        // Manejar la carga de la foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }

        // Crear una nueva instancia de Persona
        $persona = new Persona(
            $request->input('ci'), // CI
            $request->input('nombres'), // Nombres
            $request->input('apellidos'), // Apellidos
            $request->input('telefono'), // Teléfono
            $request->input('email'), // Email
            $request->input('usuario'), // Usuario
            'Administrador', // Rol (fijo para administrador)
            null, // Billetera (opcional)
            Hash::make($request->input('password')), // Password (hasheado)
            $fotoPath // Foto (opcional)
        );

        // Crear una nueva instancia de Administrador
          $administrador = new Administrador();

        // Llamar al método add de ListaAdministrador para guardar los datos
        $this->listaAdministrador->add($persona, $administrador);

        // Iniciar sesión con el nuevo usuario
        $this->listaPersona->iniciarSesion($request->input('usuario'), $request->input('password'));

        return redirect()->route('admin.home')->with('success', 'Administrador creado exitosamente.');
    }

}
