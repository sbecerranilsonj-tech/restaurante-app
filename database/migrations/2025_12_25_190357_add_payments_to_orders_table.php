<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Agregamos columnas para el detalle del pago
            $table->string('payment_method')->default('cash')->after('total'); // cash, card
            $table->decimal('received_amount', 10, 2)->nullable()->after('payment_method'); // Cuánto entregó el cliente
            $table->decimal('change_amount', 10, 2)->default(0)->after('received_amount'); // El vuelto
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'received_amount', 'change_amount']);
        });
    }
};