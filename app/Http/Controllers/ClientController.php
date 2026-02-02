<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        // Listamos clientes con conteo de órdenes
        $clients = Client::withCount('orders')->orderBy('name')->get();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'document_number' => 'nullable|unique:clients']);
        Client::create($request->all());
        return redirect()->route('clients.index')->with('success', 'Cliente registrado.');
    }

    // --- NUEVA FUNCIÓN: PERFIL 360 ---
    public function show(Client $client)
    {
        // 1. Historial de Órdenes (Completadas)
        $orders = $client->orders()
                         ->where('status', 'completed')
                         ->orderBy('created_at', 'desc')
                         ->get();

        // 2. Estadísticas Financieras
        $totalSpent = $orders->sum('total');
        $visitCount = $orders->count();
        $lastVisit = $orders->first() ? $orders->first()->created_at : null;

        // 3. Calcular Nivel VIP
        $rank = 'Nuevo';
        $badgeColor = 'secondary';
        if ($totalSpent > 1000) { $rank = 'Oro (VIP)'; $badgeColor = 'warning'; }
        elseif ($totalSpent > 500) { $rank = 'Plata'; $badgeColor = 'secondary'; }
        elseif ($totalSpent > 100) { $rank = 'Bronce'; $badgeColor = 'danger'; }

        // 4. Plato Favorito (Query avanzada)
        $favoriteDish = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_details.quantity) as total_qty'))
            ->where('orders.client_id', $client->id)
            ->groupBy('products.name')
            ->orderByDesc('total_qty')
            ->first();

        $favoriteProduct = $favoriteDish ? $favoriteDish->name . ' (' . $favoriteDish->total_qty . ' veces)' : 'Aún sin datos';

        return view('clients.show', compact('client', 'orders', 'totalSpent', 'visitCount', 'lastVisit', 'rank', 'badgeColor', 'favoriteProduct'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate(['name' => 'required', 'document_number' => 'nullable|unique:clients,document_number,'.$client->id]);
        $client->update($request->all());
        return redirect()->route('clients.index')->with('success', 'Datos actualizados.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Cliente eliminado.');
    }
}