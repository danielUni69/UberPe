<?php

namespace Database\Factories;

use app\Models\PersonaModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PersonaModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PersonaModel::class;

    public function definition()
    {
        return [
            'ci' => $this->faker->unique()->numberBetween(1000000, 9999999),
            'nombres' => $this->faker->firstName,
            'apellidos' => $this->faker->lastName,
            'telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'usuario' => $this->faker->userName,
            'password' => Hash::make('password'), // Contraseña encriptada
            'rol' => $this->faker->randomElement(['Pasajero', 'Conductor']),
            'billetera' => $this->faker->numberBetween(0, 500),
            'deuda' => 0,
        ];
    }
}
