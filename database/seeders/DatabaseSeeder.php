<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Area;
use App\Models\Table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Usuario Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'), // Clave: password
            'role' => 'admin'
        ]);

        // 2. Crear Áreas (Salones)
        $salon = Area::create(['name' => 'Salón Principal']);
        $terraza = Area::create(['name' => 'Terraza']);

        // 3. Crear Mesas
        // 5 mesas en el salón
        for ($i = 1; $i <= 5; $i++) {
            Table::create([
                'area_id' => $salon->id,
                'name' => 'Mesa ' . $i,
                'seats' => 4,
                'status' => 'available'
            ]);
        }
        // 3 mesas en terraza
        for ($i = 1; $i <= 3; $i++) {
            Table::create([
                'area_id' => $terraza->id,
                'name' => 'T-' . $i,
                'seats' => 2,
                'status' => 'available'
            ]);
        }

        // 4. Crear Categorías
        $catBebidas = Category::create(['name' => 'Bebidas', 'is_active' => true]);
        $catComidas = Category::create(['name' => 'Hamburguesas', 'is_active' => true]);
        $catPostres = Category::create(['name' => 'Postres', 'is_active' => true]);

        // 5. Crear Productos
        Product::create([
            'category_id' => $catComidas->id,
            'name' => 'Hamburguesa Clásica',
            'price' => 12.50,
            'stock' => 50,
            'is_active' => true
        ]);
        Product::create([
            'category_id' => $catComidas->id,
            'name' => 'Hamburguesa Doble Queso',
            'price' => 15.00,
            'stock' => 40,
            'is_active' => true
        ]);
        Product::create([
            'category_id' => $catBebidas->id,
            'name' => 'Coca Cola 500ml',
            'price' => 3.50,
            'stock' => 100,
            'is_active' => true
        ]);
        Product::create([
            'category_id' => $catBebidas->id,
            'name' => 'Limonada Frozen',
            'price' => 5.00,
            'stock' => null, // Ilimitado
            'is_active' => true
        ]);
        Product::create([
            'category_id' => $catPostres->id,
            'name' => 'Cheesecake Fresa',
            'price' => 8.00,
            'stock' => 10,
            'is_active' => true
        ]);
    }
}