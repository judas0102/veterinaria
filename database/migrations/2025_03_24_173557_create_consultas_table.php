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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();

            // Relacionar la consulta con la cita
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();

            // Campos para la consulta
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
