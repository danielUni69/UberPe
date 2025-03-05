<?php

namespace App\Http\Controllers;

use App\Core\ListaPersona;
use App\Core\Persona;
use App\Core\Validations\PersonaValidation;
use Illuminate\Http\Request;
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

    // Crear una nueva instancia de Persona
    $persona = new Persona(
        $request->input('ci'), // CI
        $request->input('nombres'), // Nombres
        $request->input('apellidos'), // Apellidos
        $request->input('telefono'), // Teléfono
        $request->input('email'), // Email
        $request->input('usuario'), // Usuario
        $request->input('rol', 'Pasajero'), // Rol (si no se proporciona, se asigna 'Pasajero' por defecto)
        $request->input('billetera', 0.00), // Billetera (valor predeterminado 0.00)
        $request->input('password'), // Contraseña
        null // Foto (opcional)
    );

    // Guardar la persona en la base de datos
    $this->listaPersona->add($persona);

    // Iniciar sesión con el nuevo usuario
    $this->listaPersona->iniciarSesion($request->input('usuario'), $request->input('password'));

    return redirect()->route('home')->with('success', 'Usuario creado exitosamente.');
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

        // Manejar la carga de la foto

        /*$fotoPath = $personaActual->foto;
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            // Guardar la nueva foto
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
            $request->input('billetera',0.00),
            null,
            //$fotoPath // Añadir ruta de la foto
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
        return view('persona.editar', compact('persona'));
        return view('administrador.editar', compact('persona'));

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

        $response = $this->listaPersona->cambiarPass($currentPassword, $newPassword);

        if ($response['success']) {
            return redirect()->route('home')->with('success', $response['message']);
        }

        return back()->withErrors(['error' => $response['message']]);
    }

    public function recargarBilletera(Request $request, $id)
    {
        $monto = $request->monto;
        $nuevoSaldo = $this->listaPersona->recargarBilletera($id, $monto);

        return response()->json(['nuevoSaldo' => $nuevoSaldo]);
    }
}
