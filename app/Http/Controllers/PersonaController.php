<?php

namespace App\Http\Controllers;

use App\Core\ListaPersona;
use App\Core\Persona;
use App\Core\Validations\PersonaValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    // Manejar la carga de la foto (opcional)
    /*$fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('fotos', 'public');
    }*/


    $persona = new Persona(
        $request->input('ci'), 
        $request->input('nombres'), 
        $request->input('apellidos'), 
        $request->input('telefono'), 
        $request->input('email'), 
        $request->input('usuario'), 
        $request->input('rol', 'Pasajero'), 
        $request->input('billetera', 0.00), 
        $request->input('password'), 
        null 
    );

    // Guardar la persona en la base de datos
    $this->listaPersona->add($persona);

    // Iniciar sesión con el nuevo usuario
    $this->listaPersona->iniciarSesion($request->input('usuario'), $request->input('password'));

    // Redirigir según el rol
    if ($persona->getRol() === 'Pasajero') {
        return redirect()->route('home')->with('success', 'Usuario creado exitosamente.');
    } else {
        return redirect()->route('admin.home')->with('success', 'Usuario creado exitosamente.');
    }
    }
    public function update(Request $request, $id = null)
    {
    if ($id == null) {
        $id = auth()->user()->id_persona;
    }

    // Validar los datos de entrada
    $validator = PersonaValidation::validateEdit($request->all(), $id);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Obtener la persona actual
    $personaActual = $this->listaPersona->getPersona($id);

    // Manejar la carga de la foto (opcional)
    /*$fotoPath = $personaActual->foto;
    if ($request->hasFile('foto')) {
        if ($fotoPath) {
            Storage::disk('public')->delete($fotoPath);
        }
        $fotoPath = $request->file('foto')->store('fotos', 'public');
    }*/

    // Crear una nueva instancia de Persona con los datos actualizados
    $persona = new Persona(
        $request->input('ci'),
        $request->input('nombres'),
        $request->input('apellidos'),
        $request->input('telefono'),
        $request->input('email'),
        $request->input('usuario'),
        $request->input('rol', 'Pasajero'), // Rol (si no se proporciona, se asigna 'Pasajero' por defecto)
        $request->input('billetera', 0.00), // Billetera (valor predeterminado 0.00)
        null, // Contraseña (no se actualiza en la edición)
        null // Foto (opcional)
    );

    // Actualizar la persona en la base de datos
    $this->listaPersona->edit($persona, $id);

    // Redirigir según el rol
    if ($persona->getRol() === 'Pasajero') {
        return redirect()->route('home')->with('success', 'Usuario editado exitosamente.');
    } else {
        return redirect()->route('admin.home')->with('success', 'Usuario editado exitosamente.');
    }
    }

    public function showEditarForm($id = null)
   {
    if ($id == null) {
        $id = auth()->user()->id_persona;
    }

    // Obtener la persona actual
    $persona = $this->listaPersona->getPersona($id);
    if (!$persona) {
        return redirect()->route('home')->with('error', 'Usuario no encontrado.');
    }

    // Determinar la vista según el rol
    if ($persona->getRol() === 'Pasajero') {
        return view('persona.editar', compact('persona'));
    } else {
        return view('administrador.editar', compact('persona'));
    }
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
            if (auth()->user()->rol === 'Conductor') {
                return redirect()->route('home-conductor')->with('success', 'Inicio de sesión exitoso.');
            }
            elseif (auth()->user()->rol === 'Administrador') {
                return redirect()->route('admin.home')->with('success', 'Inicio de sesión exitoso.');
            }
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

        return view('auth.login');
    }

    public function verBilletera($id)
    {
        $saldo = $this->listaPersona->verBilletera($id);

        return response()->json(['saldo' => $saldo]);
    }

    public function showCambiarPass(){
        return view ('persona.cambiarPass');
    }

    public function cambiarPass(Request $request)
{
    $currentPassword = $request->input('currentPassword');
    $newPassword = $request->input('new_password');
    $confirmPassword = $request->input('confirm_password');

    // Verificar que la nueva contraseña y la confirmación sean iguales
    if ($newPassword !== $confirmPassword) {
        return back()->withErrors(['error' => 'La nueva contraseña y la confirmación no coinciden.']);
    }

    // Cambiar la contraseña
    $response = $this->listaPersona->cambiarPass($currentPassword, $newPassword);

    // Verificar si el cambio de contraseña fue exitoso
    if ($response['success']) {
        // Obtener el rol del usuario actual
        $rol = auth()->user()->rol; // Asume que el rol está almacenado en el campo "rol" del usuario autenticado

        // Redirigir según el rol
        switch ($rol) {
            case 'Administrador':
                return redirect()->route('admin.home')->with('success', $response['message']);
            case 'Conductor':
                return redirect()->route('home-conductor')->with('success', $response['message']);
            case 'Pasajero':
            default:
                return redirect()->route('home')->with('success', $response['message']);
        }
    }

    // Si el cambio de contraseña falló, regresar con un mensaje de error
    return back()->withErrors(['error' => $response['message']]);
}

    public function recargarBilletera(Request $request, $id)
    {
        $monto = $request->monto;
        $nuevoSaldo = $this->listaPersona->recargarBilletera($id, $monto);

        return response()->json(['nuevoSaldo' => $nuevoSaldo]);
    }
}
