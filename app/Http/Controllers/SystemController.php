<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\InventoryLog;

class SystemController extends Controller
{
    public function index()
    {
        // Contamos qué vamos a borrar para informar al usuario
        $counts = [
            'orders' => Order::count(),
            'reservations' => Reservation::count(),
            'logs' => InventoryLog::count(),
        ];
        return view('system.index', compact('counts'));
    }

    public function resetData(Request $request)
    {
        $request->validate(['password' => 'required']);

        // Verificación de seguridad simple: La contraseña debe ser la del usuario actual
        if (!password_verify($request->password, auth()->user()->password)) {
            return back()->with('error', 'Contraseña incorrecta. No se realizaron cambios.');
        }

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Borrar Ventas y Detalles
            DB::table('order_details')->truncate();
            DB::table('orders')->truncate();

            // 2. Borrar Reservas
            DB::table('reservations')->truncate();

            // 3. Borrar Kardex (Opcional: A veces se quiere mantener el stock inicial, pero para reset total borramos todo)
            // Si borras el kardex, el stock en la tabla products se mantiene, pero pierdes el historial.
            // Para ser coherentes, reiniciamos el stock de productos a 0 también o borramos solo logs.
            // Aquí borraremos historial y pondremos stock a 0 para obligar a un inventario inicial real.
            DB::table('inventory_logs')->truncate();
            DB::table('products')->update(['stock' => 0]); 

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', '¡Sistema reiniciado! Ventas, Reservas y Stock han vuelto a cero.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error crítico: ' . $e->getMessage());
        }
    }
}