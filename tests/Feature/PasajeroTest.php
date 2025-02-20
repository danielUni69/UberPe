<?php

namespace Tests\Feature;

use App\Core\Services\PasajeroService;
use App\Models\ConductorModel;
use App\Models\PersonaModel;
use App\Models\ViajeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
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

        $persona = PersonaModel::factory()->create(['nombres' => 'juancito', 'rol' => 'Conductor', 'billetera' => 100]);
        $conductor = ConductorModel::factory()->create([
            'disponible' => true,
            'persona_id' => $persona->id_persona,
        ]);

        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);
        $this->actingAs($pasajero);
        $response = $this->pasajeroService->solicitarServicio('upds', 'Plaza', 'Efectivo', 15);
        // Convertir la respuesta a una instancia de TestResponse
        $response = new TestResponse($response);
        // dd($response);
        $response->assertStatus(201)->assertJson([
            'mensaje' => 'Solicitud de viaje creada exitosamente.',
        ]);
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
        $persona = PersonaModel::factory()->create();
        $this->actingAs($persona);

        $response = $this->pasajeroService->solicitarServicio('upds', 'Plaza', 'Efectivo', 15);

        $response = new TestResponse($response);
        $response->assertStatus(400)
            ->assertJson(['mensaje' => 'No hay conductores disponibles.']);
    }

    public function test_saldo_insuficiente_en_billetera()
    {
        $pasajero = PersonaModel::factory()->create(['billetera' => 10]); // Saldo menor a la tarifa
        ConductorModel::factory()->create(['disponible' => true]);
        $this->actingAs($pasajero);
        $response = $this->pasajeroService->solicitarServicio('upds', 'Plaza', 'Tarjeta', 15);

        $response = new TestResponse($response);
        $response->assertStatus(400)
            ->assertJson(['mensaje' => 'No tienes saldo suficiente en tu billetera.']);
    }

    public function test_solicitud_de_viaje_exitosa_con_tarjeta_o_billetera()
    {
        $pasajero = PersonaModel::factory()->create(['billetera' => 50]); // Saldo suficiente
        ConductorModel::factory()->create(['disponible' => true]);
        $this->actingAs($pasajero);

        $response = $this->pasajeroService->solicitarServicio('upds', 'Plaza', 'Tarjeta', 20);

        $response = new TestResponse($response);
        $response->assertStatus(201)
            ->assertJson([
                'mensaje' => 'Solicitud de viaje creada exitosamente.',
            ]);

        $this->assertDatabaseHas('viaje', [
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'Pendiente',
            'tarifa' => 20,
        ]);

        $this->assertEquals(30, $pasajero->fresh()->billetera); // Se descontó el saldo
    }

    public function test_pasajero_paga_viaje_en_efectivo()
    {
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);
        // Crear un viaje pendiente de pago
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'Completado sin pagar',
            'tarifa' => 100,
            'metodo' => 'Efectivo',
        ]);
        $this->actingAs($pasajero);
        $response = $this->pasajeroService->pagar();
        // dd($pasajero);
        $response = new TestResponse($response);
        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Pago registrado correctamente.',
            ]);

        $this->assertDatabaseHas('viaje', [
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'Viaje pagado sin confirmar por el conductor',
        ]);

        $this->assertDatabaseHas('pago', [
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => 100,
            'comision' => 10,
            'monto_conductor' => 90,
        ]);
    }

    public function test_pasajero_paga_viaje_con_otro_metodo()
    {
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'Completado sin pagar',
            'tarifa' => 200,
            'metodo' => 'Tarjeta',
        ]);

        $this->actingAs($pasajero);
        $response = $this->pasajeroService->pagar();
        // dd($pasajero);
        $response = new TestResponse($response);

        $response->assertStatus(200)
            ->assertJson([
                'mensaje' => 'Pago realizado. ¿Desea calificar al conductor?',
            ]);

        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'Completado',
        ]);
    }

    public function test_pasajero_califica_correctamente_a_un_conductor()
    {
        $persona = PersonaModel::factory()->create(['rol' => 'Pasajero']);
        $conductor = ConductorModel::factory()->create(
            ['persona_id' => $persona->id_persona]
        );
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero']);
        $this->actingAs($pasajero);
        $calificacion = 4;
        $response = $this->pasajeroService->calificarConductor($conductor->id_conductor, $calificacion);

        $response = new TestResponse($response);
        //  dd($response);
        $response->assertStatus(200)
            ->assertJson(['mensaje' => 'Calificación registrada correctamente.']);

        // Verificar que la calificación fue registrada
        $this->assertDatabaseHas('calificacion_conductor', [
            'conductor_id' => $conductor->id_conductor,
            'calificacion' => $calificacion,
        ]);
    }
}
