<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('telefono');
            $table->string('email')->unique();
            $table->string('usuario')->unique();
            $table->string('password');
            $table->enum('rol', ['Pasajero', 'Conductor', 'Administrador']);
            $table->float('billetera')->default(0);
            $table->float('deuda')->default(0);
            $table->string('licencia')->nullable();
            $table->boolean('disponible')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
