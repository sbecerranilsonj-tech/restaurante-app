<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode', // <--- NUEVO CAMPO AGREGADO
        'description',
        'price',
        'image',
        'category_id',
        'stock',
        'is_active',
        'is_saleable'
    ];

    // Relación con Categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con Ingredientes (Para el descuento de inventario)
    public function ingredients()
    {
        return $this->belongsToMany(Product::class, 'product_ingredients', 'product_id', 'ingredient_id')
                    ->withPivot('quantity');
    }

    // Relación con Detalles de Orden
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}