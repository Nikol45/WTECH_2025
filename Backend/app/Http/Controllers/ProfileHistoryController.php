<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileHistoryController extends Controller
{
    /** Zoznam objednávok prihláseného používateľa */
    public function index()
    {
        $orders = Order::with([
            'packages.farm',
            'packages.orderItems.farmProduct.product',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('profile.history', compact('orders'));
    }

    /** „Objednať znova“ – vloží položky späť do košíka */
    public function reorder(Order $order)
    {
        $this->authorize('view', $order);       // používateľ musí byť majiteľ

        DB::transaction(function () use ($order) {
            foreach ($order->packages as $package) {
                foreach ($package->orderItems as $item) {
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
