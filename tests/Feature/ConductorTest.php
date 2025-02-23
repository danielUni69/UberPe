<?php

namespace Tests\Feature;

use App\Core\Services\ConductorService;
use App\Models\CalificacionConductorModel;
use App\Models\ConductorModel;
use App\Models\PersonaModel;
use App\Models\ViajeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConductorTest extends TestCase
{
    use RefreshDatabase; // Esto asegura que la base de datos se reinicie después de cada prueba

    protected $conductorService;

    protected $persona;

    protected $conductor;

    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->conductorService = new ConductorService;
        $this->persona = PersonaModel::factory()->create(['rol' => 'Conductor']);
        $this->conductor = ConductorModel::factory()->create(['persona_id' => $this->persona->id_persona]);
    }

    public function test_viajes_pendientes_solo_para_conductores()
    {
        $conductor = PersonaModel::factory()->create(['rol' => 'Conductor']);
        $this->actingAs($conductor);

        ViajeModel::factory()->count(3)->create(['estado' => 'Pendiente']);
        ViajeModel::factory()->count(2)->create(['estado' => 'Completado']);

        $viajes = $this->conductorService->viajesPendientes();

        $this->assertCount(3, $viajes);
    }

    public function test_aceptar_viaje_exitoso()
    {
        $conductor = PersonaModel::factory()->create(['rol' => 'Conductor']);
        $this->actingAs($conductor);

        $viaje = ViajeModel::factory()->create(['estado' => 'Pendiente']);

        $viajeAceptado = $this->conductorService->aceptarViaje($viaje->id_viaje);

        $this->assertEquals('En curso', $viajeAceptado->estado);
        $this->assertEquals($conductor->id, $viajeAceptado->conductor_id);

        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'En curso',
            'conductor_id' => $conductor->id,
        ]);
    }

    public function test_finalizar_viaje_como_conductor_exitoso_metodo_tarjeta()
    {
        $persona = PersonaModel::factory()->create(['rol' => 'Conductor', 'billetera' => 100]);
        $conductor = ConductorModel::factory()->create(['persona_id' => $persona->id_persona]);
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero', 'billetera' => 200]);
        $this->actingAs($persona);

        $viaje = ViajeModel::factory()->create([
            'conductor_id' => $persona->conductor->id_conductor,
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'En curso',
            'tarifa' => 50,
            'metodo' => 'Tarjeta',
            'saldo_bloqueado' => 50,
        ]);

        $resultado = $this->conductorService->finalizarViaje();

        $this->assertEquals('Viaje finalizado correctamente.', $resultado['mensaje']);

        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'Completado',
            'saldo_bloqueado' => 0,
        ]);

        $this->assertDatabaseHas('pago', [
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => 50,
            'comision' => 5,
            'monto_conductor' => 45,
        ]);

        $this->assertEquals(145, $conductor->persona->fresh()->billetera);
        $this->assertEquals(150, $pasajero->fresh()->billetera);
    }

    public function test_finalizar_viaje_con_pago_en_efectivo()
    {
        $persona = PersonaModel::factory()->create(['rol' => 'Conductor', 'billetera' => 100]);
        $conductor = ConductorModel::factory()->create(['persona_id' => $persona->id_persona]);
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero', 'billetera' => 200]);
        $this->actingAs($persona);

        $viaje = ViajeModel::factory()->create([
            'conductor_id' => $persona->conductor->id_conductor,
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'En curso',
            'tarifa' => 50,
            'metodo' => 'Efectivo',
            'saldo_bloqueado' => 50,
        ]);

        $resultado = $this->conductorService->finalizarViaje();

        $this->assertEquals('Viaje finalizado correctamente.', $resultado['mensaje']);

        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'Viaje pagado sin confirmar por el conductor',
            'saldo_bloqueado' => 0,
        ]);

        $this->assertDatabaseHas('pago', [
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => 50,
            'comision' => 5,
            'monto_conductor' => 45,
        ]);

        $this->assertEquals(100, $conductor->persona->fresh()->billetera);
        $this->assertEquals(200, $pasajero->fresh()->billetera);
    }

    public function test_confirmar_pago_como_conductor_exitoso()
    {
        $persona = PersonaModel::factory()->create([
            'rol' => 'Conductor',
            'billetera' => 100,
        ]);
        $conductor = ConductorModel::factory()->create(['persona_id' => $persona->id_persona, 'disponible' => false]);
        $this->actingAs($persona);

        $viaje = ViajeModel::factory()->create([
            'conductor_id' => $persona->conductor->id_conductor,
            'estado' => 'Viaje pagado sin confirmar por el conductor',
            'tarifa' => 50,
        ]);

        $resultado = $this->conductorService->confirmarPago();

        $this->assertEquals('Pago confirmado. Viaje marcado como completado.', $resultado['mensaje']);

        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'Completado',
        ]);

        $this->assertTrue($conductor->fresh()->disponible);

        $this->assertDatabaseHas('pago', [
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => 50,
            'comision' => 5,
            'monto_conductor' => 45,
        ]);

        $this->assertEquals(95, $persona->fresh()->billetera);
    }

    public function test_promedio_calificacion_exitoso()
    {
        $persona = PersonaModel::factory()->create(['rol' => 'Conductor']);
        $conductor = ConductorModel::factory()->create(['persona_id' => $persona->id_persona]);
        CalificacionConductorModel::factory()->create([
            'conductor_id' => $conductor->id_conductor,
            'calificacion' => 3,
            'fecha' => now(),
        ]);
        CalificacionConductorModel::factory()->create([
            'conductor_id' => $conductor->id_conductor,
            'calificacion' => 4,
            'fecha' => now(),
        ]);
        CalificacionConductorModel::factory()->create([
            'conductor_id' => $conductor->id_conductor,
            'calificacion' => 4,
            'fecha' => now(),
        ]);
        $this->actingAs($persona);
        $response = $this->conductorService->calcularPromedioCalificaciones();
        $this->assertEquals(4, $response);
    }
}
