<?php

namespace Database\Seeders;

use App\Models\PersonaModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\ConductorModel;
class ConductorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $persona = PersonaModel::create([
            'ci' => '11223344',
            'nombres' => 'Carlos',
            'apellidos' => 'Martínez',
            'telefono' => '112233445',
            'email' => 'carlos.conductor@example.com',
            'usuario' => 'carlos',
            'password' => Hash::make('conductor123'),
            'billetera' => 2000,  
            'deuda' => 0,
            'rol' => 'Conductor',
        ]);
        ConductorModel::create([
            'persona_id' => $persona->id_persona,
            'licencia' => '123456',
            'disponible' => true,
        ]);
    }
}
