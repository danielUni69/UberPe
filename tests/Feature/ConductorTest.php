<?php

namespace Tests\Feature;

use App\Core\Services\ConductorService;
use App\Core\Services\PasajeroService;
use App\Models\ConductorModel;
use App\Models\PersonaModel;
use App\Models\ViajeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
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
        
        $this->actingAs($this->persona);

        ViajeModel::factory()->count(3)->create(['estado' => 'Pendiente']);
        ViajeModel::factory()->count(2)->create(['estado' => 'Completado']);

        // Hacer la solicitud al método viajesPendientes
        $response = $this->conductorService->viajesPendientes();        
        $response = new TestResponse($response);
        // Verificar que la respuesta es exitosa y contiene los viajes pendientes
        $response->assertStatus(200)
                 ->assertJsonCount(3); // Debería haber 3 viajes pendientes
    }
    public function test_aceptar_viaje_exitoso()
    {
        // Autenticar al usuario
        $this->actingAs($this->persona);

        // Crear un viaje pendiente en la base de datos
        $viaje = ViajeModel::factory()->create(['estado' => 'Pendiente']);

        // Hacer la solicitud al método aceptarViaje
        $response = $this->conductorService->aceptarViaje($viaje->id_viaje);
        $response = new TestResponse($response);
        // Verificar que la respuesta es exitosa
        $response->assertStatus(200);
        // Verificar que el viaje se actualizó en la base de datos
        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'En curso',
            'conductor_id' => $this->conductor->id,
        ]);
    }
    public function test_finalizar_viaje_como_conductor_exitoso_metodo_tarjeta()
    {
     
        $persona = PersonaModel::factory()->create(['rol' => 'Conductor', 'billetera' => 100]);
        $conductor = ConductorModel::factory()->create(['persona_id' => $persona->id_persona]);
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero','billetera' => 200]);
        $this->actingAs($persona);

        // Crear un viaje en curso asignado al conductor
        $viaje = ViajeModel::factory()->create([
            'conductor_id' => $persona->conductor->id_conductor,
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'En curso',
            'tarifa' => 50,
            'metodo' => 'Tarjeta',
            'saldo_bloqueado' => 50,
        ]);

        // Hacer la solicitud al método finalizarViaje
        $response = $this->conductorService->finalizarViaje();
        $response = new TestResponse($response);
        // Verificar que la respuesta es exitosa
        $response->assertStatus(200)
                 ->assertJson(['mensaje' => 'Viaje finalizado correctamente.']);

        // Verificar que el viaje se actualizó en la base de datos
        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'Completado',
            'saldo_bloqueado' => 0,
        ]);

        // Verificar que el pago se registró correctamente
        $this->assertDatabaseHas('pago', [
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => 50,
            'comision' => 5, // 10% de 50
            'monto_conductor' => 45, // 50 - 5
        ]);

        // Verificar que el saldo del conductor y el pasajero se actualizó
        $this->assertEquals(145, $conductor->persona->fresh()->billetera); // 100 + 45
        $this->assertEquals(150, $pasajero->fresh()->billetera); // 200 - 50
    }
    public function test_finalizar_viaje_con_pago_en_efectivo()
    {
        // Crear un usuario con rol de conductor
        $persona = PersonaModel::factory()->create(['rol' => 'Conductor', 'billetera' => 100]);
        $conductor = ConductorModel::factory()->create(['persona_id' => $persona->id_persona]);
        // Crear un pasajero
        $pasajero = PersonaModel::factory()->create(['rol'=> 'Pasajero', 'billetera' => 200]);

        // Autenticar al conductor
        $this->actingAs($persona);

        // Simular que el rol en sesión es "Conductor"
        $this->actingAs($persona);

        // Crear un viaje en curso asignado al conductor con pago en efectivo
        $viaje = ViajeModel::factory()->create([
            'conductor_id' => $persona->conductor->id_conductor,
            'pasajero_id' => $pasajero->id_persona,
            'estado' => 'En curso',
            'tarifa' => 50,
            'metodo' => 'Efectivo',
            'saldo_bloqueado' => 50,
        ]);

        // Hacer la solicitud al método finalizarViaje
        $response = $this->conductorService->finalizarViaje();
        $response = new TestResponse($response);

        // Verificar que la respuesta es exitosa
        $response->assertStatus(200)
                 ->assertJson(['mensaje' => 'Viaje finalizado correctamente.']);

        // Verificar que el viaje se actualizó en la base de datos
        $this->assertDatabaseHas('viaje', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'Viaje pagado sin confirmar por el conductor',
            'saldo_bloqueado' => 0,
        ]);

        // Verificar que el pago se registró correctamente
        $this->assertDatabaseHas('pago', [
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => 50,
            'comision' => 5, // 10% de 50
            'monto_conductor' => 45, // 50 - 5
        ]);

        // Verificar que el saldo del conductor y el pasajero no cambió
        $this->assertEquals(100, $conductor->persona->fresh()->billetera);
        $this->assertEquals(200, $pasajero->fresh()->billetera);
    }
    public function test_confirmar_pago_como_conductor_exitoso()
    {
        // Crear un usuario con rol de conductor y saldo suficiente
        $persona = PersonaModel::factory()->create([
            'rol' => 'Conductor',
            'billetera' => 100,
        ]);
        $conductor = ConductorModel::factory()->create(['persona_id' => $persona->id_persona, 'disponible' => false]);

        // Autenticar al conductor
        $this->actingAs($persona);

        // Crear un viaje en espera de confirmación de pago
        $viaje = ViajeModel::factory()->create([
            'conductor_id' => $persona->conductor->id_conductor,
            'estado' => 'Viaje pagado sin confirmar por el conductor',
            'tarifa' => 50,
        ]);

        // Hacer la solicitud al método confirmarPago
        $response = $this->conductorService->confirmarPago();
        $response = new TestResponse($response);

        // Verificar que la respuesta es exitosa
        $response->assertStatus(200)
                 ->assertJson(['mensaje' => 'Pago confirmado. Viaje marcado como completado.']);

        // Verificar que el viaje se actualizó en la base de datos
        $this->assertDatabaseHas('viajes', [
            'id_viaje' => $viaje->id_viaje,
            'estado' => 'Completado',
        ]);

        // Verificar que el conductor está disponible
        $this->assertTrue($conductor->fresh()->disponible);

        // Verificar que el pago se registró correctamente
        $this->assertDatabaseHas('pagos', [
            'viaje_id' => $viaje->id_viaje,
            'monto_total' => 50,
            'comision' => 5, // 10% de 50
            'monto_conductor' => 45, // 50 - 5
        ]);

        // Verificar que el saldo del conductor se actualizó
        $this->assertEquals(95, $conductor->fresh()->billetera); // 100 - 5
    }

}