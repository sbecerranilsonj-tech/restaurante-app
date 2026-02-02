<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        // Listamos productos con su categoría, ordenados por los más nuevos
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // 1. Validación (Incluye el barcode único)
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|max:50|unique:products,barcode', // <--- NUEVO
            'image' => 'nullable|image|max:2048',
            'stock' => 'nullable|integer'
        ]);

        $data = $request->all();
        
        // 2. Manejo de Imagen
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Checkbox de "Disponible en POS" (si no viene, es false)
        $data['is_saleable'] = $request->has('is_saleable');
        $data['is_active'] = true;

        // 3. Crear Producto
        $product = Product::create($data);

        // 4. Registro inicial en Kardex si hay stock
        if($request->stock > 0) {
            InventoryLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'entry',
                'quantity' => $request->stock,
                'old_stock' => 0,
                'new_stock' => $request->stock,
                'note' => 'Inventario Inicial'
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        // Productos que pueden ser insumos (todos menos él mismo)
        $ingredients = Product::where('id', '!=', $product->id)->where('is_active', true)->get();
        
        return view('products.edit', compact('product', 'categories', 'ingredients'));
    }

    public function update(Request $request, Product $product)
    {
        // 1. Validación (Barcode único excepto para este producto)
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id, // <--- NUEVO
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // 2. Manejo de Imagen
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['is_saleable'] = $request->has('is_saleable');

        // 3. Actualizar
        $product->update($data);

        // Actualizar receta/insumos (Si enviaste array de ingredientes)
        if ($request->has('ingredients')) {
            $syncData = [];
            foreach ($request->ingredients as $id => $qty) {
                if ($qty > 0) $syncData[$id] = ['quantity' => $qty];
            }
            $product->ingredients()->sync($syncData);
        }

        return redirect()->route('products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        // Eliminado lógico (desactivar) en lugar de borrar para mantener historial
        $product->update(['is_active' => false]);
        return redirect()->route('products.index')->with('success', 'Producto eliminado (desactivado).');
    }

    // Funciones extra para ajustes rápidos
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back();
    }

    public function adjustStock(Request $request, Product $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1', 'type' => 'required|in:add,sub']);
        
        $oldStock = $product->stock;
        $qty = $request->quantity;
        
        if ($request->type === 'sub') {
            $product->decrement('stock', $qty);
            $newStock = $oldStock - $qty;
            $type = 'adjustment_out';
        } else {
            $product->increment('stock', $qty);
            $newStock = $oldStock + $qty;
            $type = 'adjustment_in';
        }

        InventoryLog::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'type' => $type,
            'quantity' => ($request->type === 'sub' ? -$qty : $qty),
            'old_stock' => $oldStock,
            'new_stock' => $newStock,
            'note' => 'Ajuste manual desde panel'
        ]);

        return back()->with('success', 'Stock ajustado.');
    }
}