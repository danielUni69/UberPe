<?php

namespace Database\Factories;

use App\Models\ConductorModel;
use App\Models\PersonaModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppModelsConductorModel>
 */
class ConductorModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ConductorModel::class;

    public function definition(): array
    {
        return [
            // Relación con PersonaModel, se crea una persona aleatoria
            'persona_id' => PersonaModel::factory(), // Esto genera una persona nueva para cada conductor

            // Atributos adicionales del conductor
            'licencia' => $this->faker->bothify('??-######'), // Licencia del conductor (ejemplo: AB-123456)
            'disponible' => $this->faker->boolean, // Conductor disponible o no
        ];
    }
}
