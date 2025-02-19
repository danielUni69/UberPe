<?php

use App\Models\ConductorModel;
use App\Models\Pago;
use App\Models\PasajeroModel;
use App\Models\SancionModel;
use App\Models\Viaje;

class AdministradorService
{
    public function gestionConductor()
    {
        $conductores = ConductorModel::all(); // Obtener todos los conductores

        return response()->json($conductores); // Retorna la lista de conductores en formato JSON
    }

    public function gestionPasajero()
    {
        $pasajeros = PasajeroModel::all(); // Obtener todos los pasajeros

        return response()->json($pasajeros); // Retorna la lista de pasajeros en formato JSON
    }

    public function sancionar()
    {
        $this->gestionPasajero();
        $nombre = readline('Nombre de persona a sancionar: ');

        // Buscar la persona por su nombre
        $persona = PasajeroModel::where('nombres', $nombre)->first();

        if ($persona) {
            $sancion = new SancionModel([
                'persona_id' => $persona->id, // Asegúrate de que 'persona_id' esté en la tabla de sanciones
                'motivo' => readline('Motivo: '),
                'tipo' => readline('Tipo (Leve/Moderado/Grave): '),
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addWeek(), // Sanción de 1 semana
                'estado' => 'Activo',
            ]);
            $sancion->save(); // Guardar la sanción en la base de datos

            return response()->json(['message' => 'Sanción aplicada exitosamente!'], 200); // Retornar respuesta en JSON
        }

        return response()->json(['message' => 'Persona no encontrada'], 404); // Retornar error si la persona no existe
    }

    public function historialViajesGeneral()
    {
        $viajes = Viaje::all(); // Obtener todos los viajes

        return response()->json($viajes); // Retorna la lista de viajes en formato JSON
    }

    public function verGanancias()
    {
        $pagos = Pago::all(); // Obtener todos los pagos

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

        return response()->json([
            'pagos' => $resultado,
            'total_ganancias' => $total,
        ]);
    }
}
