<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // Mostrar lista de categorías
    public function index()
    {
        $categories = Category::all();
        // Nota: Aún no tenemos la vista 'categories.index', la crearemos en el siguiente paso
        return view('categories.index', compact('categories'));
    }

    // Guardar una nueva categoría en BD
    public function store(Request $request)
    {
        // 1. Validar datos (Profesional)
        $request->validate([
            'name' => 'required|unique:categories,name|max:50',
            'image' => 'nullable|image|max:2048' // Max 2MB
        ]);

        // 2. Manejo de imagen si existe
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        // 3. Crear registro
        Category::create([
            'name' => $request->name,
            'image' => $imagePath,
            'is_active' => true
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoría creada con éxito.');
    }

    // Actualizar categoría existente
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:50|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only('name');

        if ($request->hasFile('image')) {
            // Borrar imagen anterior si existe
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada.');
    }

    // Eliminar categoría
    public function destroy(Category $category)
    {
        // Opcional: Validar si tiene productos antes de borrar para evitar errores
        if($category->products()->count() > 0){
             return redirect()->route('categories.index')->with('error', 'No puedes eliminar una categoría con productos asociados.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada.');
    }
}