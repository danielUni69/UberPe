<?php

namespace Database\Factories;

use App\Models\CalificacionConductorModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalificacionConductorModelFactory extends Factory
{
    protected $model = CalificacionConductorModel::class;

    public function definition()
    {
        return [
            'conductor_id' => 1,
            'calificacion' => $this->faker->numberBetween(1, 5),
            'fecha' => $this->faker->date(),
        ];
    }
}
