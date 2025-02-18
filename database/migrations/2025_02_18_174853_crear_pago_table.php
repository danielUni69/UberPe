<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago'); // Clave primaria
            $table->unsignedBigInteger('viaje_id'); // Clave foránea
            $table->decimal('monto_total', 10, 2);
            $table->decimal('comision', 10, 2);
            $table->decimal('monto_conductor', 10, 2);
            $table->date('fecha');
            $table->timestamps();

            // Relación con la tabla viaje
            $table->foreign('viaje_id')->references('id_viaje')->on('viaje')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pago');
    }
};
