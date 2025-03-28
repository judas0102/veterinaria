<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            // Opcional: Relacionar con el usuario que agenda la cita
            // $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Nombre de la mascota

            $table->foreignId('mascota_id')->nullable()->constrained();


            // Fecha y hora de la cita
            $table->dateTime('fecha_hora');

            // Estado de la cita (pendiente, completada, cancelada, etc.)
            $table->string('estado')->default('pendiente');

            // Observaciones adicionales
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
