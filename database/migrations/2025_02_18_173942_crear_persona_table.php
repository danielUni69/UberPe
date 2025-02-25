<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id('id_persona'); // Clave primaria
            $table->string('ci')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('telefono');
            $table->string('email')->unique();
            $table->string('usuario')->unique();
            $table->string('password');
            $table->decimal('billetera', 10, 2)->default(0);
            $table->decimal('deuda', 10, 2)->default(0);
            $table->enum('rol', ['Administrador', 'Conductor', 'Pasajero']);
            $table->string('foto')->nullable(); // Añadir campo foto
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('persona');
    }
};
