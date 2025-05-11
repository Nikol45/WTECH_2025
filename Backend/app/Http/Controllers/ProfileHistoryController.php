<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileHistoryController extends Controller
{
    /* Zoznam objednávok prihláseného používateľa */
    public function index()
    {
        $orders = Order::with([
            'packages.farm',
            'packages.order_items.farm_product.product',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('profile.history', compact('orders'));
    }

    /* vloží položky späť do košíka */
    public function reorder(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        DB::transaction(function () use ($order) {
            foreach ($order->packages as $package) {
                foreach ($package->order_items as $item) {
                    CartItem::updateOrCreate(
                        [
                            'user_id'         => $order->user_id,
                            'farm_product_id' => $item->farm_product_id,
                        ],
                        ['quantity' => DB::raw('quantity + ' . $item->quantity)]
                    );
                }
            }
        });

        return back()->with('success', 'Produkty boli pridané do košíka.');
    }
}
