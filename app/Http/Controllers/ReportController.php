<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filtro de Fechas (Default: Inicio de mes hasta hoy)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // 1. VENTAS POR CATEGORÍA (Gráfico de Dona)
        $salesByCategory = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_details.quantity * order_details.price) as total'))
            ->where('orders.status', 'completed')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->groupBy('categories.name')
            ->get();

        $catLabels = $salesByCategory->pluck('name');
        $catValues = $salesByCategory->pluck('total');

        // 2. RENDIMIENTO DE PERSONAL (Gráfico de Barras)
        $salesByWaiter = Order::select('users.name', DB::raw('SUM(total) as total_sales'), DB::raw('COUNT(orders.id) as orders_count'))
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.status', 'completed')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->groupBy('users.name')
            ->orderByDesc('total_sales')
            ->get();

        $waiterLabels = $salesByWaiter->pluck('name');
        $waiterValues = $salesByWaiter->pluck('total_sales');

        // 3. TOP 5 PLATOS MÁS VENDIDOS
        $topProducts = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->select('products.name', DB::raw('SUM(order_details.quantity) as qty'), DB::raw('SUM(order_details.quantity * order_details.price) as revenue'))
            ->where('orders.status', 'completed')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->groupBy('products.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        // 4. TOP 5 PLATOS MENOS VENDIDOS (Para tomar acción)
        $worstProducts = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->select('products.name', DB::raw('SUM(order_details.quantity) as qty'))
            ->where('orders.status', 'completed')
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->groupBy('products.name')
            ->orderBy('qty', 'asc')
            ->limit(5)
            ->get();

        // Moneda
        $currency = \App\Models\Setting::where('key', 'currency_symbol')->value('value') ?? 'S/';

        return view('reports.index', compact(
            'startDate', 'endDate', 
            'catLabels', 'catValues', 
            'waiterLabels', 'waiterValues',
            'topProducts', 'worstProducts', 'salesByWaiter', 'currency'
        ));
    }
}