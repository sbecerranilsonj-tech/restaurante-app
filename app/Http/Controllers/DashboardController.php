<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Area;
use App\Models\Setting;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Configuración de Fecha y Moneda
        $currency = Setting::where('key', 'currency_symbol')->value('value') ?? 'S/';
        $today = Carbon::today(); // Usa la fecha del servidor/app

        // 2. KPIs (Indicadores Principales)
        // Sumamos solo las ventas con estatus 'completed' de HOY
        $totalSalesToday = Order::where('status', 'completed')
                                ->whereDate('created_at', $today)
                                ->sum('total');

        $ordersCountToday = Order::where('status', 'completed')
                                 ->whereDate('created_at', $today)
                                 ->count();

        // Mesas Activas (Cualquier orden que no esté 'completed' ni 'cancelled')
        $activeTables = Order::where('status', 'pending')->count();

        // Stock Crítico (Conteo de productos con stock <= 5)
        $lowStockProducts = Product::where('is_active', true)
                                   ->where('stock', '<=', 5)
                                   ->count();

        // 3. Monitor de Mesas (Para el mapa visual)
        // Cargamos áreas con mesas y sus órdenes activas para pintar rojo/verde
        $areas = Area::with(['tables' => function($q) {
            $q->with(['orders' => function($o) {
                $o->where('status', 'pending'); // Solo nos interesan las activas
            }]);
        }])->get();

        // 4. Gráfico de Ventas (Últimos 7 días)
        $chartLabels = [];
        $chartValues = [];
        
        // Generamos los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->locale('es')->isoFormat('dd D'); // Ej: "lun 12"
            
            // Consulta la suma de ese día específico
            $daySum = Order::where('status', 'completed')
                           ->whereDate('created_at', $date->format('Y-m-d'))
                           ->sum('total');
            $chartValues[] = $daySum;
        }

        // 5. Top Productos Vendidos (Histórico General o del Mes)
        $topProducts = DB::table('order_details')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.status', 'completed') // Solo ventas reales
            ->select('products.name', 'products.image', DB::raw('SUM(order_details.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'currency',
            'totalSalesToday',
            'ordersCountToday',
            'activeTables',
            'lowStockProducts',
            'areas',
            'chartLabels',
            'chartValues',
            'topProducts'
        ));
    }
}