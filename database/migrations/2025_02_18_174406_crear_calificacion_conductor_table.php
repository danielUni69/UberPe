<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calificacion_conductor', function (Blueprint $table) {
            $table->id('id_calificacion'); // Clave primaria
            $table->unsignedBigInteger('conductor_id'); // Clave foránea
            $table->decimal('calificacion', 3, 2); // Ejemplo: 4.75
            $table->date('fecha');
            $table->timestamps();

            // Relación con la tabla conductor
            $table->foreign('conductor_id')->references('id_conductor')->on('conductor')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('calificacion_conductor');
    }
};
