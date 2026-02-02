<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('client_name'); // Nombre de quien reserva
            $table->string('phone')->nullable(); // Para confirmar
            $table->dateTime('reservation_time'); // Fecha y Hora
            $table->integer('people'); // Cantidad de personas
            $table->foreignId('table_id')->nullable()->constrained()->onDelete('set null'); // Mesa asignada
            $table->text('note')->nullable(); // Ej: "Cumpleaños, Alérgico a nueces"
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};