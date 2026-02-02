<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    // Pantalla principal del KDS
    public function index()
    {
        // Buscamos órdenes que tengan platos pendientes o cocinando
        // Ordenamos por las más antiguas primero (FIFO - First In, First Out)
        $orders = Order::whereHas('details', function($q) {
                            $q->whereIn('status', ['pending', 'cooking']);
                        })
                        ->with(['table', 'details' => function($q) {
                            $q->whereIn('status', ['pending', 'cooking']);
                        }])
                        ->orderBy('created_at', 'asc')
                        ->get();

        return view('kitchen.index', compact('orders'));
    }

    // Avanzar estado del plato: Pendiente -> Cocinando -> Servido
    public function updateStatus(OrderDetail $detail)
    {
        if ($detail->status == 'pending') {
            $detail->update(['status' => 'cooking']);
        } elseif ($detail->status == 'cooking') {
            $detail->update(['status' => 'served']);
        }

        // Retornamos al KDS
        return redirect()->route('kitchen.index'); // En una versión avanzada, esto sería AJAX
    }
}