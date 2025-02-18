<?php

namespace App\Http\Controllers;

use App\Core\ListaPersona;
use App\Core\Persona;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    private ListaPersona $personas;

    public function __construct()
    {
        $this->personas = new ListaPersona;
    }

    public function index()
    {
        $personas = $this->personas->list();
        dd($personas, $personas[0]->convertToPersona());
        // return view('personas.index', compact('personas'));
    }

    public function store(Request $request)
    {
        $persona = new Persona(
            $request->ci,
            $request->nombres,
            $request->apellidos,
            $request->telefono,
            $request->email,
            $request->usuario,
            $request->password,
            $request->rol,
            $request->billetera,
            $request->deuda
        );
        $this->listaPersona->add($persona);

        return redirect()->route('personas.index');
    }

    public function iniciarSesion(Request $request)
    {
        $usuario = $request->usuario;
        $password = $request->password;
        $persona = $this->listaPersona->iniciarSesion($usuario, $password);
        if ($persona) {
            return response()->json(['success' => true, 'persona' => $persona]);
        }

        return response()->json(['success' => false, 'message' => 'Credenciales incorrectas']);
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
