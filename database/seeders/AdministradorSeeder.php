<?php

namespace Database\Seeders;

use App\Models\PersonaModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PersonaModel::create([
            'ci' => '12345678',
            'nombres' => 'Juan',
            'apellidos' => 'Pérez',
            'telefono' => '123456789',
            'email' => 'juan.admin@example.com',
            'usuario' => 'juan',

            'password' => Hash::make('admin123'),
            'billetera' => 1000,
            'deuda' => 0,
            'rol' => 'Administrador',
        ]);
    }
}
