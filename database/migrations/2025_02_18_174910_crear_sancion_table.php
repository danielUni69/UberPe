<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sancion', function (Blueprint $table) {
            $table->id('id_sancion'); // Clave primaria
            $table->unsignedBigInteger('persona_id'); // Clave foránea
            $table->unsignedBigInteger('reclamo_id'); // Clave foránea
            $table->text('motivo');
            $table->enum('tipo', ['Leve', 'Moderado', 'Grave']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['Activo', 'Expirada']);
            $table->timestamps();

            // Relaciones
            $table->foreign('persona_id')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->foreign('reclamo_id')->references('id_reclamo')->on('reclamo')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sancion');
    }
};
