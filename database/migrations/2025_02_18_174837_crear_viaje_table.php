<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('viaje', function (Blueprint $table) {
            $table->id('id_viaje'); // Clave primaria
            $table->unsignedBigInteger('pasajero_id'); // Clave foránea
            $table->unsignedBigInteger('conductor_id')->nullable(); // Clave foránea
            $table->string('origen');
            $table->string('destino');
            $table->dateTime('fecha');
            $table->enum('metodo', ['Efectivo', 'Tarjeta', 'Billetera']);
            $table->enum('estado', ['Pendiente', 'En curso', 'Completado', 'Completado sin pagar', 'Completado sin confirmar', 'Cancelado', 'Cancelado por el pasajero', 'Cancelado por el conductor', 'Viaje pagado sin confirmar por el conductor', 'Sin viajes']);
            $table->decimal('tarifa', 10, 2);
            $table->decimal('saldo_bloqueado', 10, 2)->default(0);
            $table->timestamps();
            // Relaciones
            $table->foreign('pasajero_id')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->foreign('conductor_id')->references('id_conductor')->on('conductor')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('viaje');
    }
};
