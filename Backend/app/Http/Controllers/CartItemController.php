<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        if (auth()->check()) {

            CartItem::create([
                'user_id' => auth()->id(),
                'farm_product_id' => $request->farm_product_id,
                'quantity' => $request->quantity,
            ]);
        }
        else {
            $cart = session()->get('cart', []);
            $cart[] = [
                'farm_product_id' => $request->farm_product_id,
                'quantity' => $request->quantity,
            ];
            session(['cart' => $cart]);
        }

        return redirect()->back()->with('success', 'Produkt bol pridaný do košíka.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }
}
