<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehiculo', function (Blueprint $table) {
            $table->id('id_vehiculo'); // Clave primaria
            $table->unsignedBigInteger('conductor_id'); // Clave foránea
            $table->string('marca');
            $table->string('modelo');
            $table->string('placa')->unique();
            $table->string('color');
            $table->timestamps();

            // Relación con la tabla conductor
            $table->foreign('conductor_id')->references('id_conductor')->on('conductor')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehiculo');
    }
};
