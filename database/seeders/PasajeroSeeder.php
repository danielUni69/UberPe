<?php

namespace Database\Seeders;

use App\Models\PersonaModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PasajeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PersonaModel::create([
            'ci' => '87654321',
            'nombres' => 'María',
            'apellidos' => 'Gómez',
            'telefono' => '987654321',
            'email' => 'maria.pasajero@example.com',
            'usuario' => 'maria',
            'password' => Hash::make('pasajero123'),
            'billetera' => 500,
            'deuda' => 0,
            'rol' => 'Pasajero',
        ]);
    }
}
