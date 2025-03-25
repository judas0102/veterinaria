<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->string('cliente')->nullable()->after('nombre_mascota');
            $table->string('telefono')->nullable()->after('cliente');
        });
    }

    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn(['cliente', 'telefono']);
        });
    }
};
