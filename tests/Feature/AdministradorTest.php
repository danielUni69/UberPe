<?php

namespace Tests\Feature;

use App\Core\Services\AdministradorService;
use App\Models\ConductorModel;
use App\Models\PersonaModel;
use App\Models\ReclamoModel;
use App\Models\SancionModel;
use App\Models\ViajeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministradorTest extends TestCase
{
    use RefreshDatabase; // Esto asegura que la base de datos se reinicie después de cada prueba

    protected $administradorService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administradorService = new AdministradorService;  // Inicia correctamente el servicio

    }

    public function test_aplicar_sancion_exitosa()
    {
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);
        $viaje = ViajeModel::factory()->create();
        $persona = PersonaModel::factory()->create(['rol' => 'Conductor']);
        $conductor = ConductorModel::factory()->create(['persona_id' => $persona->id_persona]);

        $reclamo = ReclamoModel::create([
            'persona_id' => $pasajero->id_persona,
            'viaje_id' => $viaje->id_viaje,
            'motivo' => 'Conducción temeraria',
            'fecha' => now(),
        ]);
        $san = new SancionModel([
            'persona_id' => $persona->id_persona,
            'reclamo_id' => $reclamo->id_reclamo,
            'motivo' => 'Conducción temeraria',
            'tipo' => 'Leve',
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addDays(7),
            'estado' => 'Activo',
        ]);

        $sancion = $this->administradorService->sancionar($persona->id_persona, $san);

        $this->assertDatabaseHas('sancion', [
            'persona_id' => $persona->id_persona,
            'motivo' => 'Conducción temeraria',
            'tipo' => 'Leve',
            'estado' => 'Activo',
        ]);
        // dd($persona->sancion);
        $this->assertEquals($persona->id_persona, $sancion->persona_id);
    }
}
