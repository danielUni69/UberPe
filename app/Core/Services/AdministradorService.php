<?php



namespace App\Core\Services;

use App\Core\Administrador;


use App\Core\Persona;

use App\Models\AdministradorModel;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


use App\Models\ConductorModel;
use App\Models\PagoModel;
use App\Models\PersonaModel;
use App\Models\SancionModel;
use App\Models\VehiculoModel;
use App\Models\ViajeModel;

class AdministradorService
{
    public function getConductores()
    {
        return $conductores = ConductorModel::all();
    }
    public function getVehiculos(){
        return $vehiculos = VehiculoModel::all();

    }

    public function getPasajeros()
    {
        return $pasajeros = PersonaModel::all();
    }


    /**
     * Crea un nuevo administrador y su persona asociada.
     *
     * @param Persona $persona
     * @param Administrador $administrador
     * @return PersonaModel
     */
    public function add(Persona $persona)
    {
        // Crear la Persona
        $personaModel = new PersonaModel;
        $personaModel->ci = $persona->getCi();
        $personaModel->nombres = $persona->getNombres();
        $personaModel->apellidos = $persona->getApellidos();
        $personaModel->telefono = $persona->getTelefono();
        $personaModel->email = $persona->getEmail();
        $personaModel->usuario = $persona->getUsuario();
        $personaModel->password = Hash::make($persona->getPassword());
        $personaModel->rol = 'Administrador'; // Rol fijo para administradores
        $personaModel->billetera = 0;
        $personaModel->deuda = 0;
        $personaModel->save();



        return $personaModel;
    }

       /**
     * Edita un administrador y su persona asociada.
     *
     * @param int $id
     * @param Persona $persona
     * @param Administrador $administrador
     * @return PersonaModel
     * @throws \Exception
     */
    public function edit($id, Persona $persona)
    {
        // Buscar la Persona asociada al administrador
        $personaModel = PersonaModel::find($id);

        if (!$personaModel) {
            throw new \Exception('Persona no encontrada.', 404);
        }

        // Actualizar los campos de Persona
        $personaModel->ci = $persona->getCi();
        $personaModel->nombres = $persona->getNombres();
        $personaModel->apellidos = $persona->getApellidos();
        $personaModel->telefono = $persona->getTelefono();
        $personaModel->email = $persona->getEmail();
        $personaModel->usuario = $persona->getUsuario();

        // Si se proporciona una nueva contraseña, se encripta
        if ($persona->getPassword()) {
            $personaModel->password = Hash::make($persona->getPassword());
        }

        $personaModel->rol = 'Administrador'; // Rol fijo para administradores
        $personaModel->billetera = $persona->getBilletera();
        $personaModel->deuda = $persona->getDeuda();
        $personaModel->save();



        return $personaModel;
    }

    public function sancionar($id, SancionModel $sancion)
    {
        $persona = PersonaModel::find($id);

        if (! $persona) {
            throw new \Exception('Persona no encontrada.', 404);
        }

        $sancion->persona_id = $persona->id_persona;
        $sancion->save();

        return $sancion;
    }

    public function historialViajesGeneral()
    {
        return $viajes = ViajeModel::all();
    }

    public function verGanancias()
    {
        $pagos = PagoModel::all(); // Obtener todos los pagos

        $total = 0;
        $resultado = [];

        foreach ($pagos as $p) {
            $resultado[] = [
                'monto_total' => $p->monto_total,
                'comision' => $p->comision,
                'pago_conductor' => $p->monto_conductor,
                'fecha' => $p->fecha,
            ];
            $total += (float) $p->comision;
        }

        return [
            'pagos' => $resultado,
            'total_ganancias' => $total,
        ];
    }
}
