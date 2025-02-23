<?php

namespace Tests\Feature;

use App\Core\Services\PasajeroService;
use App\Models\ConductorModel;
use App\Models\PersonaModel;
use App\Models\ViajeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasajeroTest extends TestCase
{
    use RefreshDatabase; // Esto asegura que la base de datos se reinicie después de cada prueba

    protected $pasajeroService;

    /**
     * A basic feature test example.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->pasajeroService = new PasajeroService;
    }

    public function test_solicitar_servicio_metodo_efectivo()
    {
        // Crear un conductor disponible
        $conductor = ConductorModel::factory()->create(['disponible' => true]);

        // Crear un pasajero
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);
        $this->actingAs($pasajero);

        // Llamar al método del servicio
        $viaje = $this->pasajeroService->solicitarServicio('upds', 'Plaza', 'Efectivo', 15);

        // Verificar que el viaje se haya creado correctamente
        $this->assertDatabaseHas('viaje', [
            'pasajero_id' => $pasajero->id_persona,
            'conductor_id' => null,
            'origen' => 'upds',
            'destino' => 'Plaza',
            'estado' => 'Pendiente',
            'tarifa' => 15,
            'metodo' => 'Efectivo',
            'saldo_bloqueado' => 15,
        ]);
    }

    public function test_no_hay_conductores_disponibles()
    {
        // Crear un pasajero
        $pasajero = PersonaModel::factory()->create();
        $this->actingAs($pasajero);

        // Esperar una excepción
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No hay conductores disponibles.');

        // Llamar al método del servicio
        $this->pasajeroService->solicitarServicio('upds', 'Plaza', 'Efectivo', 15);
    }

    public function test_saldo_insuficiente_en_billetera()
    {
        // Crear un conductor disponible
        $conductor = ConductorModel::factory()->create(['disponible' => true]);

        // Crear un pasajero con saldo insuficiente
        $pasajero = PersonaModel::factory()->create(['billetera' => 10]);
        $this->actingAs($pasajero);

        // Esperar una excepción
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No tienes saldo suficiente en tu billetera.');

        // Llamar al método del servicio
        $this->pasajeroService->solicitarServicio('upds', 'Plaza', 'Tarjeta', 15);
    }

    public function test_solicitud_de_viaje_exitosa_con_tarjeta_o_billetera()
    {
        // Crear un conductor disponible
        $conductor = ConductorModel::factory()->create(['disponible' => true]);

        // Crear un pasajero con saldo suficiente
        $pasajero = PersonaModel::factory()->create(['billetera' => 50]);
        $this->actingAs($pasajero);

        // Llamar al método del servicio
        $viaje = $this->pasajeroService->solicitarServicio('upds', 'Plaza', 'Tarjeta', 20);

        // Verificar que el viaje se haya creado correctamente
        $this->assertDatabaseHas('viaje', [
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'Pendiente',
            'tarifa' => 20,
        ]);

        // Verificar que el saldo del pasajero se haya descontado correctamente
        $this->assertEquals(30, $pasajero->fresh()->billetera);
    }

    public function test_pasajero_paga_viaje_en_efectivo()
    {
        // Crear un pasajero
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);

        // Crear un viaje pendiente de pago
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'Completado sin pagar',
            'tarifa' => 100,
            'metodo' => 'Efectivo',
        ]);

        $this->actingAs($pasajero);

        // Llamar al método del servicio
        $resultado = $this->pasajeroService->pagar();

        // Verificar la respuesta
        $this->assertEquals('Pago registrado correctamente.', $resultado['mensaje']);

        // Verificar que el viaje se haya actualizado correctamente
        $this->assertDatabaseHas('viaje', [
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'Viaje pagado sin confirmar por el conductor',
        ]);

        // Verificar que el pago se haya creado correctamente
        $this->assertDatabaseHas('pago', [
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => 100,
            'comision' => 10,
            'monto_conductor' => 90,
        ]);
    }

    public function test_pasajero_paga_viaje_con_otro_metodo()
    {
        // Crear un pasajero
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);

        // Crear un viaje pendiente de pago
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'Completado sin pagar',
            'tarifa' => 200,
            'metodo' => 'Tarjeta',
        ]);

        $this->actingAs($pasajero);

        // Llamar al método del servicio
        $resultado = $this->pasajeroService->pagar();

        // Verificar la respuesta
        $this->assertEquals('Pago realizado. ¿Desea calificar al conductor?', $resultado['mensaje']);

        // Verificar que el viaje se haya actualizado correctamente
        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'Completado',
        ]);
    }

    public function test_pasajero_califica_correctamente_a_un_conductor()
    {
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);

        $conductor = ConductorModel::factory()->create();

        $this->actingAs($pasajero);

        $calificacion = 4;

        $resultado = $this->pasajeroService->calificarConductor($conductor->id_conductor, $calificacion);

        $this->assertEquals('Calificación registrada correctamente.', $resultado['mensaje']);

        $this->assertDatabaseHas('calificacion_conductor', [
            'conductor_id' => $conductor->id_conductor,
            'calificacion' => $calificacion,
        ]);
    }

    public function test_pasajero_califica_con_valor_fuera_de_rango()
    {
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);

        $conductor = ConductorModel::factory()->create();

        $this->actingAs($pasajero);

        $calificacion = 6;

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('La calificación debe estar entre 1 y 5.');

        $this->pasajeroService->calificarConductor($conductor->id_conductor, $calificacion);
    }

    public function test_pasajero_ve_historial_de_viajes()
    {
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);

        $viajes = ViajeModel::factory()->count(3)->create(['pasajero_id' => $pasajero->id_persona]);

        $this->actingAs($pasajero);

        $historial = $this->pasajeroService->verHistorialViajesPasajero();

        $this->assertCount(3, $historial);
        $this->assertEquals($viajes->pluck('id_viaje'), $historial->pluck('id_viaje'));
    }
}
