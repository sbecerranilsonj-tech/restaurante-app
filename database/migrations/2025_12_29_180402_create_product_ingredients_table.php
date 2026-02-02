<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // El Plato (Hamburguesa)
            $table->foreignId('ingredient_id')->constrained('products')->onDelete('cascade'); // El Insumo (Pan)
            $table->decimal('quantity', 10, 2); // Cuánto usa (Ej: 0.200 kg o 1 unidad)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_ingredients');
    }
};