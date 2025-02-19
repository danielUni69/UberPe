<?php

namespace Database\Factories;

use App\Models\PersonaModel;
use App\Models\ViajeModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ViajeModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ViajeModel::class;

    public function definition()
    {
        return [
            'pasajero_id' => PersonaModel::factory(), // Crea un pasajero automáticamente
            'conductor_id' => null, // Sin conductor asignado
            'origen' => $this->faker->city, // Origen aleatorio (puedes personalizarlo)
            'destino' => $this->faker->city, // Destino aleatorio (puedes personalizarlo)
            'fecha' => $this->faker->dateTimeThisMonth, // Fecha aleatoria dentro de este mes
            'metodo' => $this->faker->randomElement(['Efectivo', 'Tarjeta']), // Método de pago aleatorio
            'estado' => $this->faker->randomElement(['Pendiente', 'En curso', 'Completado', 'Completado sin pagar', 'Completado sin confirmar', 'Cancelado']), // Estado aleatorio
            'tarifa' => $this->faker->randomFloat(2, 10, 1000), // Tarifa aleatoria entre 10 y 1000
            'saldo_bloqueado' => $this->faker->randomFloat(2, 0, 100), // Saldo bloqueado aleatorio
        ];
    }
}
