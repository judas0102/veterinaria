<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            // Esta línea está duplicada y ya está en create_citas_table -> eliminar
            // $table->unsignedBigInteger('mascota_id')->nullable()->after('id');

            // Solo usar si estas columnas realmente existen antes
            // $table->string('nombre_mascota')->nullable()->change();
            // $table->string('cliente')->nullable()->change();

            $table->string('motivo')->nullable()->after('cliente');
            // $table->string('telefono')->nullable()->after('motivo');
            // $table->string('estado')->default('pendiente')->after('fecha_hora');
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['motivo']);
        });
    }
};
