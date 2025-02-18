<?php
namespace Tests\Feature;

use App\Core\ListaPersona;
use App\Core\Persona;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonaTest extends TestCase
{
    use RefreshDatabase; // Esto asegura que la base de datos se reinicie después de cada prueba

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_add_persona(): void
    {
        // Crear una instancia de ListaPersona
        $listaPersona = new ListaPersona();

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
}
