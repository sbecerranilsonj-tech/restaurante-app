<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Area;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $areas = Area::with('tables')->get();
        return view('tables.index', compact('areas'));
    }

    public function storeArea(Request $request)
    {
        $request->validate(['name' => 'required']);
        Area::create($request->all());
        return redirect()->back()->with('success', 'Zona creada.');
    }

    public function destroyArea(Area $area)
    {
        $area->tables()->delete();
        $area->delete();
        return redirect()->back()->with('success', 'Zona eliminada.');
    }

    public function storeTable(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'area_id' => 'required|exists:areas,id'
        ]);

        Table::create([
            'name' => $request->name,
            'area_id' => $request->area_id,
            'status' => 'available',
            'x_pos' => 50, // Posición por defecto visible
            'y_pos' => 50
        ]);

        return redirect()->back()->with('success', 'Mesa creada.');
    }

    public function destroyTable(Table $table)
    {
        $table->delete();
        return redirect()->back()->with('success', 'Mesa eliminada.');
    }

    // --- FUNCIÓN DE GUARDADO DE MAPA ---
    public function updatePositions(Request $request)
    {
        // Validamos que llegue un array
        $positions = $request->input('positions');

        if (!is_array($positions)) {
            return response()->json(['status' => 'error', 'message' => 'Datos inválidos'], 400);
        }

        foreach($positions as $pos) {
            // Buscamos la mesa y actualizamos
            $table = Table::find($pos['id']);
            if($table) {
                $table->x_pos = (int) $pos['x'];
                $table->y_pos = (int) $pos['y'];
                $table->save();
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Mapa guardado correctamente']);
    }
}