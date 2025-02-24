<?php

namespace App\Http\Controllers;

use App\Core\ListaPersona;
use App\Core\Persona;
use App\Core\Validations\PersonaValidation;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    private ListaPersona $listaPersona;

    public function __construct()
    {
        $this->listaPersona = new ListaPersona;
    }

    public function index()
    {
        return view('home.home');
    }

    /**
     * Crea un nuevo usuario.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        // Validar los datos con PersonaValidation
        $validator = PersonaValidation::validateAdd($request->all());
        if ($validator->fails()) {
            return redirect()->route('registro')
                ->withErrors($validator)
                ->withInput();
        }
        // Si la validación pasa, crear la persona
        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            bcrypt($request->input('password')), // Encriptar contraseña
            'Pasajero',
            $request->input('billetera')
        );

        $response = $this->listaPersona->add($persona);

        return redirect()->route('home')->with('success', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, $id = null)
    {
        if ($id == null) {
            $id = auth()->user()->id_persona;
        }

        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            'Pasajero',
            $request->input('billetera')
        );

        $response = $this->listaPersona->edit($persona, $id);

        return redirect()->route('home')->with('success', 'Usuario editado exitosamente.');
    }

    public function showEditarForm($id = null)
    {

        if ($id == null) {
            $persona = $this->listaPersona->getPersona(auth()->user()->id_persona);
        } else {
            $persona = $this->listaPersona->getPersona($id);
        }
        if (! $persona) {
            return redirect()->route('home')->with('error', 'Pasajero no encontrado.');
        }

        // Mostrar el formulario de edición con los datos del pasajero
        return view('persona.registro', compact('persona'));
    }

    /**
     * Inicia sesión.
     * $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistroForm()
    {
        return view('persona.registro');
    }

    public function login(Request $request)
    {
        $usuario = $request->input('usuario');
        $password = $request->input('password');
        if ($this->listaPersona->iniciarSesion($usuario, $password)) {
            return redirect()->route('home')->with('success', 'Inicio de sesión exitoso.');
        }

        return back()->withErrors(['error' => 'Credenciales incorrectas.']);
    }

    public function cancelarViaje()
    {
        $this->listaPersona->cancelarViaje();
    }

    /**
     * Cierra la sesión.
     */
    public function logout()
    {
        $this->listaPersona->cerrarSesion();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }

    public function verBilletera($id)
    {
        $saldo = $this->listaPersona->verBilletera($id);

        return response()->json(['saldo' => $saldo]);
    }

    public function recargarBilletera(Request $request, $id)
    {
        $monto = $request->monto;
        $nuevoSaldo = $this->listaPersona->recargarBilletera($id, $monto);

        return response()->json(['nuevoSaldo' => $nuevoSaldo]);
    }
}
