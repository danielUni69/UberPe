<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reclamo', function (Blueprint $table) {
            $table->id('id_reclamo'); // Clave primaria
            $table->unsignedBigInteger('persona_id');
            $table->unsignedBigInteger('viaje_id'); // Clave foránea
            $table->text('motivo');
            $table->date('fecha');
            $table->timestamps();

            // Relación con la tabla persona
            $table->foreign('persona_id')->references('id_persona')->on('persona')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reclamo');
    }
};
