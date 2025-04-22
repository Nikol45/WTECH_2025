<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartSummaryController extends Controller
{
    // Rekapitulácia objednávky
    public function index()
    {
        $items    = session('cart.items', []);
        $customer = session('cart.form', []);
        $delivery = session('cart.delivery', []);

        // Súhrnné ceny môžeš dopočítať tu alebo v servise
        return view('cart.summary', compact('items', 'customer', 'delivery'));
    }

    // Finálne odoslanie objednávky
    public function store(Request $request)
    {
        DB::transaction(function () {
            // 1) Vytvor objednávku
            //    $order = Order::create([...]);

            // 2) Vytvor položky
            //    foreach (session('cart.items', []) as $item) { OrderItem::create([...]); }

            // 3) Prípadne ulož adresu, dopravu, platbu...

            // 4) Vyčisti session
            session()->forget('cart');
        });

        return redirect()->route('orders.index')
            ->with('success', 'Objednávka bola úspešne odoslaná.');
    }
}
