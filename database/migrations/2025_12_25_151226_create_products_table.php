<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable(); // Código de barras o interno
            $table->decimal('price', 10, 2); // Precio de venta
            $table->decimal('cost', 10, 2)->nullable(); // Costo (para reportes de ganancia)
            $table->integer('stock')->nullable(); // Null si es servicio ilimitado
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};