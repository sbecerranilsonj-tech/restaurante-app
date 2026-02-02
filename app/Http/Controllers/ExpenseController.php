<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.1',
        ]);

        Expense::create([
            'description' => $request->description,
            'amount' => $request->amount,
            'user_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Gasto registrado correctamente.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->back()->with('success', 'Gasto eliminado.');
    }
}