<?php

namespace Tests\Feature;

use App\Core\ListaPersona;
use App\Core\Persona;
use App\Core\Services\PersonaService;
use App\Models\ConductorModel;
use App\Models\PersonaModel;
use App\Models\Viaje;
use App\Models\ViajeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PersonaTest extends TestCase
{
    use RefreshDatabase; // Esto asegura que la base de datos se reinicie después de cada prueba

    protected $personaService;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->personaService = new PersonaService;  // Inicia correctamente el servicio
    }

    public function test_add_persona(): void
    {
        // Crear una instancia de ListaPersona
        $listaPersona = new ListaPersona;

        // Crear una nueva persona
        $newPersona = new Persona(
            '12345678', // CI
            'Juan',     // Nombres
            'Perez',     // Apellidos
            '123456789', // Teléfono
            'juan@example.com', // Email
            'juanperez', // Usuario
            'password123', // Password
            'Pasajero',     // Rol
            100.0       // Billetera
        );

        // Añadir la persona a la lista
        $listaPersona->add($newPersona);

        // Obtener la lista de personas
        $personas = $listaPersona->list();

        // Verificar que la persona se haya añadido correctamente
        $this->assertCount(1, $personas);
        $this->assertEquals('Juan', $personas[0]->nombres);
        $this->assertEquals('Perez', $personas[0]->apellidos);
        $this->assertEquals('juan@example.com', $personas[0]->email);
    }

    public function test_pasajero_cancela_viaje_sin_conductor()
    {
        // Crear un usuario pasajero
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero', 'billetera' => 100]);

        // Crear un viaje sin conductor asignado
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $pasajero->id_persona,
            'conductor_id' => null,
            'estado' => 'Pendiente',
            'metodo' => 'Tarjeta',
            'saldo_bloqueado' => 20,
        ]);

        Auth::login($pasajero);
        $response = $this->personaService->cancelarViaje();
        // Verificar la respuesta
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'mensaje' => 'Viaje cancelado exitosamente.',
                'estado' => 'Cancelado por el pasajero',
            ]),
            $response->getContent()
        );

        $this->assertEquals(0, $viaje->fresh()->saldo_bloqueado);
        $this->assertEquals(100, $pasajero->fresh()->billetera);
    }

    public function test_pasajero_cancela_viaje_con_conductor()
    {
        // Crear un usuario pasajero y un conductor
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero', 'billetera' => 100]);
        $persona = PersonaModel::factory()->create(['rol' => 'Conductor', 'billetera' => 100]);
        $conductor = ConductorModel::factory()->create([
            'disponible' => true,
            'persona_id' => $persona->id_persona, // Asegúrate de asociar el conductor con una persona existente
        ]);
        // Crear un viaje con conductor asignado
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $pasajero->id_persona,
            'conductor_id' => $conductor->id_persona,
            'estado' => 'Pendiente',
            'metodo' => 'Tarjeta',
            'saldo_bloqueado' => 20,
        ]);

        Auth::login($pasajero);

        $response = $this->personaService->cancelarViaje();

        // Verificar la respuesta

        $this->assertEquals('Cancelado por el pasajero', $viaje->fresh()->estado);
        // Verificar que el saldo bloqueado se haya reiniciado
        $this->assertEquals(0, $viaje->fresh()->saldo_bloqueado);

        // Verificar que el saldo del pasajero se haya decrementado correctamente
        $this->assertEquals(98, $pasajero->fresh()->billetera);

        // Verificar que el saldo del conductor se haya incrementado correctamente
        $this->assertEquals(102, $conductor->fresh()->billetera);

        // Verificar que el conductor esté disponible
        $this->assertTrue($conductor->fresh()->disponible);
    }
}
