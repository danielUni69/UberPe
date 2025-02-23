<?php

namespace App\Core\Services;

use App\Models\ConductorModel;
use App\Models\PagoModel;
use App\Models\PersonaModel;
use App\Models\SancionModel;
use App\Models\ViajeModel;

class AdministradorService
{
    public function getConductores()
    {
        return $conductores = ConductorModel::all();
    }

    public function getPasajeros()
    {
        return $pasajeros = PersonaModel::all();
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
