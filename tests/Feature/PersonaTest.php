<?php

// nombres Jose daniel Basilio Nina, Marbin Mamamani, Rodrigo burgoa

namespace Tests\Feature;

use App\Core\ListaPersona;
use App\Core\Pasajero;
use App\Core\Persona;
use App\Core\Services\PasajeroService;
use App\Core\Services\PersonaService;
use App\Models\ConductorModel;
use App\Models\PersonaModel;
use App\Models\Viaje;
use App\Models\ViajeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PersonaTest extends TestCase
{
    use RefreshDatabase; // Esto asegura que la base de datos se reinicie después de cada prueba

    protected $personaService;

    protected $pasajeroService;

    private ListaPersona $listaPersona;

    protected function setUp(): void
    {
        parent::setUp();

        $this->listaPersona = new ListaPersona;
        $this->personaService = new PersonaService;  // Inicia correctamente el servicio
        $this->pasajeroService = new PasajeroService;

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

    public function test_add_invalid_persona_missing_required_fields(): void
    {
        $this->expectException(ValidationException::class);

        $invalidPersona = new Persona(
            '',
            '',
            '',
            '',
            'invalid-email',
            '',
            '',
            'invalid-rol',
            -50
        );

        $this->listaPersona->add($invalidPersona);
    }

    private function createValidPersona(): Persona
    {
        return new Persona(
            '12345678',
            'Juan',
            'Perez',
            '123456789',
            'juan@example.com',
            'juanperez',
            'password123',
            'Pasajero',
            100.0
        );
    }

    public function test_add_persona_with_duplicate_unique_fields(): void
    {
        $this->expectException(ValidationException::class);

        $persona1 = $this->createValidPersona();
        $this->listaPersona->add($persona1);

        $persona2 = $this->createValidPersona();
        $this->listaPersona->add($persona2);
    }

    public function test_login_with_invalid_credentials(): void
    {

        $this->expectException(ValidationException::class);
        $persona = $this->createValidPersona();
        $this->listaPersona->add($persona);

        $this->listaPersona->iniciarSesion('', '');
    }

    public function test_recargar_billetera_valid(): void
    {
        $persona = $this->createValidPersona();
        $this->listaPersona->add($persona);
        $id = $this->listaPersona->list()->first()->id_persona;

        $nuevoSaldo = $this->listaPersona->recargarBilletera($id, 50.50);

        $this->assertEquals(150.50, $nuevoSaldo);
    }

    public function test_recargar_billetera_invalid_monto(): void
    {
        $this->expectException(ValidationException::class);

        $persona = $this->createValidPersona();
        $this->listaPersona->add($persona);
        $id = $this->listaPersona->list()->first()->id_persona; // Usa id_persona

        $this->listaPersona->recargarBilletera($id, -100);
    }

    public function test_ver_billetera(): void
    {
        $persona = $this->createValidPersona();
        $this->listaPersona->add($persona);
        $id = $this->listaPersona->list()->first()->id_persona;

        $saldo = $this->listaPersona->verBilletera($id);

        $this->assertEquals(100.0, $saldo);
    }

    /*public function test_pasajero_cancela_viaje_sin_conductor()
    {
        // Crear un usuario pasajero
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero', 'billetera' => 100]);

        // Crear un viaje sin conductor asignado
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $pasajero->id_persona,
            'conductor_id' => null,
            'estado' => 'Pendiente',
            'metodo' => 'Billetera',
            'saldo_bloqueado' => 20,
        ]);

        Auth::login($pasajero);

        // Llamar al método del servicio
        $viajeCancelado = $this->personaService->cancelarViaje();

        // Verificar que el viaje se haya cancelado correctamente
        $this->assertEquals('Cancelado por el pasajero', $viajeCancelado->estado);
        $this->assertEquals(0, $viajeCancelado->saldo_bloqueado);
        $this->assertEquals(100, $pasajero->fresh()->billetera);
    }

    public function test_pasajero_cancela_viaje_con_conductor()
    {
        // Crear un usuario pasajero
        $pasajero = PersonaModel::factory()->create(['rol' => 'Pasajero', 'billetera' => 100]);

        // Crear un conductor
        $personaConductor = PersonaModel::factory()->create(['rol' => 'Conductor', 'billetera' => 100]);
        $conductor = ConductorModel::factory()->create([
            'disponible' => true,
            'persona_id' => $personaConductor->id_persona,
        ]);

        // Crear un viaje con conductor asignado
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $pasajero->id_persona,
            'conductor_id' => $conductor->id_conductor,
            'estado' => 'En curso',
            'metodo' => 'Tarjeta',
            'tarifa' => 20,
            'saldo_bloqueado' => 20,
        ]);

        Auth::login($pasajero);
        $pasajero->billetera = 80;
        // Llamar al método del servicio
        $viajeCancelado = $this->personaService->cancelarViaje();

        // Verificar que el viaje se haya cancelado correctamente
        $this->assertEquals('Cancelado por el pasajero', $viajeCancelado->estado);
        $this->assertEquals(0, $viajeCancelado->saldo_bloqueado);

        // Verificar que el saldo del pasajero se haya ajustado correctamente
        $this->assertEquals(98, $pasajero->fresh()->billetera);

        // Verificar que el saldo del conductor se haya incrementado correctamente
        $this->assertEquals(102, $personaConductor->fresh()->billetera);

        // Verificar que el conductor esté disponible
        $this->assertTrue($conductor->fresh()->disponible);
    }*/

    public function test_pasajero_cancela_viaje(){
        //d('antes');
        dump('persona creada');
        $persona = PersonaModel::factory()->create(['rol' => 'Pasajero', 'billetera' => 100]);
        $viaje = ViajeModel::factory()->create([
            'pasajero_id' => $persona->id_persona,
            'conductor_id' => null,
            'origen' => 'aqui',
            'estado' => 'Pendiente',
            'tarifa' => 10,
            'metodo' => 'Billetera',
            'saldo_bloqueado' => 10,
        ]);
        dump('hola perra');
        dump($persona->nombres);
        $this->actingAs($persona);
        //dd($viaje);
        $responde = $this->personaService->cancelarViaje();
        dump($responde);
        dd($persona->fresh()->billetera);
    }
}
