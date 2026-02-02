<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Models\Expense; // Importamos el modelo
use Illuminate\Http\Request;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        // Filtro de rango de fechas
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // 1. Obtener Ventas
        $orders = Order::whereDate('created_at', '>=', $startDate)
                       ->whereDate('created_at', '<=', $endDate)
                       ->where('status', 'completed')
                       ->orderBy('created_at', 'desc')
                       ->with('user')
                       ->get();

        // 2. Totales Ventas
        $totalCash = $orders->where('payment_method', 'cash')->sum('total');
        $totalCard = $orders->where('payment_method', 'card')->sum('total');
        $totalSales = $totalCash + $totalCard;

        // 3. Obtener Gastos (Lista y Total)
        $expenses = Expense::whereDate('created_at', '>=', $startDate)
                           ->whereDate('created_at', '<=', $endDate)
                           ->orderBy('created_at', 'desc')
                           ->with('user')
                           ->get();

        $totalExpenses = $expenses->sum('amount');

        // 4. Balance Final
        $balance = $totalCash - $totalExpenses;

        return view('sales.index', compact(
            'orders', 'expenses', 'startDate', 'endDate', 
            'totalCash', 'totalCard', 'totalSales', 'totalExpenses', 'balance'
        ));
    }

    public function ticket(Order $order)
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $settings['currency_symbol'] = $settings['currency_symbol'] ?? 'S/';
        return view('sales.ticket', compact('order', 'settings'));
    }

    public function dailyReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));
        
        $orders = Order::whereDate('created_at', '>=', $startDate)
                       ->whereDate('created_at', '<=', $endDate)
                       ->where('status', 'completed')
                       ->get();
        
        $stats = [
            'start_date' => Carbon::parse($startDate),
            'end_date' => Carbon::parse($endDate),
            'cash' => $orders->where('payment_method', 'cash')->sum('total'),
            'card' => $orders->where('payment_method', 'card')->sum('total'),
            'orders_count' => $orders->count(),
            'expenses' => 0
        ];

        if(class_exists('\App\Models\Expense')) {
            $stats['expenses'] = Expense::whereDate('created_at', '>=', $startDate)
                                        ->whereDate('created_at', '<=', $endDate)
                                        ->sum('amount');
        }

        $stats['total'] = $stats['cash'] + $stats['card'];
        $stats['balance'] = $stats['cash'] - $stats['expenses'];

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('sales.daily_report', compact('stats', 'settings'));
    }
}