<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->change();
        });
    }

};
