<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Nuevos campos para facturación
            $table->string('document_type')->default('Ticket')->after('status'); // Ticket, Boleta, Factura
            $table->string('client_name')->default('Público General')->after('document_type');
            $table->string('client_document')->nullable()->after('client_name'); // DNI o RUC
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'client_name', 'client_document']);
        });
    }
};