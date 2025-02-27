<?php

namespace App\Http\Controllers;

use App\Core\Conductor;
use App\Core\ListaConductor;
use App\Core\ListaPersona;
use App\Core\Persona;
use App\Core\Vehiculo;
use Illuminate\Http\Request;
use App\Core\Validations\ConductorValidation; // Importa la clase de validaciones
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ConductorController extends Controller
{
    private ListaConductor $listaConductor;
    private ListaPersona $listaPersona;

    public function __construct()
    {
        $this->listaConductor = new ListaConductor;
        $this->listaPersona = new ListaPersona;
    }

    public function index(){
        $conduct = $this->listaConductor->getConductor(auth()->user()->id_persona);
        $dis = $conduct['conductor']->getDisponible();
        return view('conductor.home', ['disponible' => $dis]);

    }
    public function showRegistroForm()
    {
        return view('conductor.registro');
    }

    public function store(Request $request)
    {
        // Validar los datos de entrada
        $validator = ConductorValidation::validateAdd($request->all());

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        //dd($request->all());
        // Manejar la carga de la foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        }
        $fotoVehiculoPath = null;
    
        if ($request->hasFile('foto_vehiculo')) {
            $fotoVehiculoPath = $request->file('foto_vehiculo')->store('vehiculos', 'public');
        }

        // Crear una nueva persona
        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            'Conductor',
            $request->input('billetera'),
            $request->input('password'),
            $fotoPath 
        );

        // Crear un nuevo conductor
        $conductor = new Conductor(
            $request->input('licencia'),
            $request->input('disponible')
        );

        // Crear un nuevo vehículo
        $vehiculo = new Vehiculo(
            $request->input('marca'),
            $request->input('modelo'),
            $request->input('placa'),
            $request->input('color'),
            $fotoVehiculoPath
        );
        
        // Añadir el conductor, persona y vehículo a la lista
        $response = $this->listaConductor->add($persona, $conductor, $vehiculo);

        // Iniciar sesión con el nuevo usuario
        $this->listaPersona->iniciarSesion($response->usuario, $request->input('password'));

        // Redirigir a la página de inicio con un mensaje de éxito
        return redirect()->route('home-conductor')->with('success', 'Usuario creado exitosamente.');
    }

    public function showEditConductorForm($id = null)
    {
        if ($id == null) {
            $id = auth()->user()->id_persona;
            $conductor = $this->listaConductor->getConductor($id);
        } else {
            $conductor = $this->listaConductor->getConductor($id);
        }

        return view('conductor.editar', ['conductor' => $conductor['conductor'], 'persona' => $conductor['persona']]);
    }

    public function update(Request $request, $id = null)
    {
        if ($id == null) {
            $id = auth()->user()->id_persona;
        }
        //dd($request);
        // Validar los datos de entrada
        $validator = ConductorValidation::validateEdit($request->all(), $id);
        //dd($validator);
        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            dd($validator->errors());
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
            'Conductor',
            null,
            null,
            $fotoPath 
        );

        // Crear un nuevo conductor con los datos actualizados
        $conductor = new Conductor(
            $request->input('licencia'),
            $request->input('disponible')
        );

        // Actualizar el conductor y la persona en la lista
        $this->listaConductor->edit($id, $persona, $conductor);

        // Redirigir a la página de inicio con un mensaje de éxito
        return redirect()->route('home-conductor')->with('success', 'Usuario actualizado exitosamente.');
    }
    public function cambiarEstado(){
        $this->listaConductor->cambiarEstado();
    }
}
