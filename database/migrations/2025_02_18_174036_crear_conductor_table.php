<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conductor', function (Blueprint $table) {
            $table->id('id_conductor'); // Clave primaria
            $table->unsignedBigInteger('persona_id'); // Clave foránea
            $table->string('licencia');
            $table->boolean('disponible')->default(true);
            $table->timestamps();

            // Relación con la tabla persona
            $table->foreign('persona_id')->references('id_persona')->on('persona')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('conductor');
    }
};
