<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Table;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\InventoryLog;
use App\Models\Client;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PosController extends Controller
{
    public function index()
    {
        $areas = Area::with(['tables' => function($q) {
            $q->with(['orders' => function($q) {
                $q->where('status', 'pending');
            }, 'reservations' => function($q) {
                $q->where('status', 'confirmed')
                  ->whereDate('reservation_time', Carbon::today())
                  ->where('reservation_time', '>=', Carbon::now()->subHours(2)) 
                  ->orderBy('reservation_time', 'asc');
            }]);
        }])->get();
        
        $currency = Setting::where('key', 'currency_symbol')->value('value') ?? 'S/';
        return view('pos.index', compact('areas', 'currency'));
    }

    public function order(Table $table)
    {
        // Filtro: Solo productos activos y vendibles
        $categories = Category::with(['products' => function($q) {
            $q->where('is_active', true)
              ->where('is_saleable', true);
        }])->where('is_active', true)->get();

        $order = Order::where('table_id', $table->id)->where('status', 'pending')->with('details.product')->first();
        $occupiedTableIds = Order::where('status', 'pending')->pluck('table_id');
        $freeTables = Table::whereNotIn('id', $occupiedTableIds)->where('id', '!=', $table->id)->with('area')->get();
        $clients = Client::select('id', 'name', 'document_number')->orderBy('name')->get();
        $currency = Setting::where('key', 'currency_symbol')->value('value') ?? 'S/';

        return view('pos.order', compact('table', 'categories', 'order', 'freeTables', 'clients', 'currency'));
    }

    // --- AGREGAR POR CLIC (Normal) ---
    public function addToOrder(Request $request, Table $table)
    {
        $product = Product::findOrFail($request->product_id);
        $this->addItemToTable($table, $product);
        return $this->getCartHtml($table);
    }

    // --- AGREGAR POR CÓDIGO DE BARRAS (Nuevo) ---
    public function addByBarcode(Request $request, Table $table)
    {
        $request->validate(['barcode' => 'required']);

        $product = Product::where('barcode', $request->barcode)
                          ->where('is_active', true)
                          ->where('is_saleable', true)
                          ->first();

        if (!$product) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        $this->addItemToTable($table, $product);
        
        // Devolvemos el HTML actualizado
        return $this->getCartHtml($table);
    }

    // Lógica auxiliar para no repetir código al agregar
    private function addItemToTable(Table $table, Product $product)
    {
        DB::transaction(function() use ($table, $product) {
            $order = Order::firstOrCreate(
                ['table_id' => $table->id, 'status' => 'pending'], 
                ['user_id' => auth()->id() ?? 1, 'total' => 0]
            );

            $detail = $order->details()->where('product_id', $product->id)->first();

            if ($detail) {
                $detail->increment('quantity');
            } else {
                $order->details()->create([
                    'product_id' => $product->id, 
                    'quantity' => 1, 
                    'price' => $product->price, 
                    'status' => 'pending'
                ]);
            }
            $this->recalculateTotal($order);
        });
    }

    // --- ACTUALIZAR CANTIDAD (Corregido para devolver HTML) ---
    public function updateQuantity(Request $request, OrderDetail $detail)
    {
        $newQty = $request->quantity;
        $order = $detail->order;
        
        if ($newQty < 1) { 
            $detail->delete(); 
        } else { 
            $detail->update(['quantity' => $newQty]); 
        }
        
        $this->recalculateTotal($order);
        return $this->getCartHtml($order->table);
    }

    // --- ACTUALIZAR NOTA (Corregido para devolver HTML) ---
    public function updateNote(Request $request, OrderDetail $detail) 
    { 
        $detail->update(['note' => $request->note]); 
        return $this->getCartHtml($detail->order->table); 
    }

    // --- ELIMINAR ITEM (Corregido para devolver HTML) ---
    public function removeItem(OrderDetail $detail) 
    { 
        $order = $detail->order; 
        $detail->delete(); 
        $this->recalculateTotal($order); 
        return $this->getCartHtml($order->table); 
    }

    // --- APLICAR DESCUENTO (Corregido para devolver HTML) ---
    public function applyDiscount(Request $request, Order $order) 
    { 
        $order->discount = $request->input('discount', 0); 
        $order->tip = $request->input('tip', 0); 
        $order->save(); 
        $this->recalculateTotal($order); 
        return $this->getCartHtml($order->table); 
    }
    
    public function moveTable(Request $request, Order $order) {
        $request->validate(['target_table_id' => 'required|exists:tables,id']);
        if (Order::where('table_id', $request->target_table_id)->where('status', 'pending')->exists()) return redirect()->back()->with('error', 'Ocupada.');
        $order->table_id = $request->target_table_id; $order->save();
        return redirect()->route('pos.order', $request->target_table_id);
    }

    public function getSplitContent(Order $order) { return view('pos.partials.split_content', compact('order')); }
    public function processSplit(Request $request, Order $order) { return redirect()->back(); }
    public function precheck(Order $order) { $settings = Setting::pluck('value', 'key')->toArray(); return view('sales.ticket', compact('order', 'settings')); }
    public function kitchenTicket(Order $order) { return view('sales.kitchen_ticket', compact('order')); }

    public function checkout(Request $request, Order $order)
    {
        if($order->status !== 'pending') return redirect()->route('pos.index')->with('error', 'Orden cerrada.');

        $method = $request->input('payment_method', 'cash');
        $received = $method === 'cash' ? $request->input('received_amount') : $order->total;
        $change = max(0, $received - $order->total);
        $clientId = $request->input('client_id');
        $clientName = $clientId ? Client::find($clientId)->name : ($request->input('client_name') ?? 'Público');

        DB::transaction(function() use ($order, $method, $received, $change, $request, $clientId, $clientName) {
            $order->update([
                'status' => 'completed',
                'payment_method' => $method,
                'received_amount' => $received,
                'change_amount' => $change,
                'document_type' => $request->input('document_type', 'Ticket'),
                'client_id' => $clientId, 
                'client_name' => $clientName,
                'client_document' => $request->input('client_document')
            ]);

            foreach($order->details as $detail) {
                $product = $detail->product;
                $ingredients = $product->ingredients;

                if ($ingredients->count() > 0) {
                    foreach ($ingredients as $ingredient) {
                        $qtyToDeduct = $ingredient->pivot->quantity * $detail->quantity;
                        $oldStock = $ingredient->stock;
                        $ingredient->decrement('stock', $qtyToDeduct);
                        InventoryLog::create([
                            'product_id' => $ingredient->id,
                            'user_id' => Auth::id(),
                            'type' => 'sale',
                            'quantity' => -$qtyToDeduct,
                            'old_stock' => $oldStock,
                            'new_stock' => $oldStock - $qtyToDeduct,
                            'note' => 'Venta: ' . $product->name . ' (Orden #' . $order->id . ')'
                        ]);
                    }
                } else {
                    if (!is_null($product->stock)) {
                        $oldStock = $product->stock;
                        $product->decrement('stock', $detail->quantity);
                        InventoryLog::create([
                            'product_id' => $product->id,
                            'user_id' => Auth::id(),
                            'type' => 'sale',
                            'quantity' => -($detail->quantity),
                            'old_stock' => $oldStock,
                            'new_stock' => $oldStock - $detail->quantity,
                            'note' => 'Venta POS #' . $order->id
                        ]);
                    }
                }
            }
        });

        return redirect()->route('pos.index')->with('success', 'Venta registrada.');
    }

    private function recalculateTotal(Order $order)
    {
        $subtotal = $order->details->sum(fn($d) => $d->price * $d->quantity);
        $total = ($subtotal - ($order->discount ?? 0)) + ($order->tip ?? 0);
        $order->update(['total' => max(0, $total)]);
    }

    private function getCartHtml(Table $table)
    {
        $order = Order::where('table_id', $table->id)->where('status', 'pending')->with('details.product')->first();
        $clients = Client::select('id', 'name', 'document_number')->orderBy('name')->get();
        $currency = Setting::where('key', 'currency_symbol')->value('value') ?? 'S/';
        return view('pos.partials.cart', compact('order', 'clients', 'currency'))->render();
    }
}