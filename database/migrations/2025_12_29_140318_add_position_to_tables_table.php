<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            // Coordenadas X e Y (en píxeles o porcentaje)
            $table->integer('x_pos')->default(0)->after('status');
            $table->integer('y_pos')->default(0)->after('x_pos');
        });
    }

    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn(['x_pos', 'y_pos']);
        });
    }
};