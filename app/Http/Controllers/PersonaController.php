<?php

namespace App\Http\Controllers;

use App\Core\ListaPersona;
use App\Core\Persona;
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
        $personas = $this->listaPersona->list();
        dd($personas, $personas[0]->convertToPersona());
        // return view('personas.index', compact('personas'));
    }

    /**
     * Crea un nuevo usuario.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function crearUsuario(Request $request)
    {
        $persona = new Persona(
            $request->input('ci'),
            $request->input('nombres'),
            $request->input('apellidos'),
            $request->input('telefono'),
            $request->input('email'),
            $request->input('usuario'),
            $request->input('password'),
            $request->input('rol'),
            $request->input('billetera')
        );

        $this->listaPersona->add($persona);

        // return redirect()->route('')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Inicia sesión.
     * $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        return view('persona.registro');
    }

    public function login(Request $request)
    {
        $usuario = $request->input('usuario');
        $password = $request->input('password');

        if ($this->listaPersona->iniciarSesion($usuario, $password)) {
            return redirect()->route('dashboard')->with('success', 'Inicio de sesión exitoso.');
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
