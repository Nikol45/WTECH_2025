<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\FarmProduct;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            // logged-in user: pull from DB
            $dbItems = CartItem::with(['farmProduct.product.image','farmProduct.farm'])
                        ->where('user_id', Auth::id())
                        ->get();

            $cart = $dbItems->map(function($ci) {
                $fp = $ci->farmProduct;
                return [
                    'fp'       => $fp,
                    'quantity' => $ci->quantity,
                ];
            });

        } else {
            // guest: pull from session
            $cart = session('cart', []); // [ farm_product_id => quantity, ... ]
            if (empty($cart)) {
                $cart = collect();
            } else {
                $ids = array_filter(array_keys($cart), fn($k) => is_numeric($k));
                $fps = FarmProduct::with(['product.image','farm'])
                       ->whereIn('id', $ids)
                       ->get();

                $cart = $fps->map(function($fp) use ($cart) {
                    return [
                        'fp'       => $fp,
                        'quantity' => $cart[$fp->id],
                    ];
                });
            }
        }
        $cartByFarm = $cart->groupBy(fn($item) => $item['fp']->farm->id);

        return view('cart.index', ['cart'=>$cart, 'cartByFarm' => $cartByFarm]);
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
    public function store(Request $request)
{
    $data = $request->validate([
        'farm_product_id' => 'required|int|exists:farm_products,id',
        'quantity'        => 'required|numeric|min:1',
    ]);

    $fp = FarmProduct::findOrFail($data['farm_product_id']);

    if (Auth::check()) {
        $user = Auth::user();

        $item = CartItem::firstOrNew([
            'user_id'         => $user->id,
            'farm_product_id' => $fp->id,
        ]);

        $item->quantity = $item->exists
            ? $item->quantity + $data['quantity']
            : $data['quantity'];

        $item->save();

        // 🟡 Doplníme položky do session pre summary
        $cartItems = CartItem::with('farmProduct.product', 'farmProduct.farm')
            ->where('user_id', $user->id)
            ->get();

        Session::put('cart.items', $cartItems->map(function ($item) {
            return [
                'farm_product_id' => $item->farm_product_id,
                'quantity'        => $item->quantity,
                'label'           => $item->farmProduct->product->name ?? 'Produkt',
                'price'           => $item->farmProduct->price_sell_quantity,
                'farm_id'         => $item->farmProduct->farm_id,
            ];
        })->toArray());

    } else {
        // hosť: session
        $cart = session()->get('cart', []);
        if (isset($cart[$fp->id])) {
            $cart[$fp->id] += $data['quantity'];
        } else {
            $cart[$fp->id] = $data['quantity'];
        }
        session()->put('cart', $cart);
    }

    return back()->with('success', 'Produkt pridaný do košíka.');
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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
{
    $ids = $request->input('items', []); // [ 12, 42, … ]

    if (Auth::check()) {
        // remove from database
        CartItem::where('user_id', Auth::id())
                ->whereIn('farm_product_id', $ids)
                ->delete();
    } else {
        // remove from session
        $cart = session()->get('cart', []);
        foreach ($ids as $id) {
            unset($cart[$id]);
        }
        session(['cart' => $cart]);
    }

    return redirect()
        ->route('cart-items.index')
        ->with('success','Vybrané položky boli odstránené.');
}

public function update(Request $request, $farmProductId)
{
    $data = $request->validate([
      'quantity' => 'required|integer|min:1',
    ]);

    if (Auth::check()) {
        $item = CartItem::firstOrNew([
          'user_id'         => Auth::id(),
          'farm_product_id' => $farmProductId,
        ]);
        $item->quantity = $data['quantity'];
        $item->save();
    } else {
        $cart = session()->get('cart', []);
        $cart[$farmProductId] = $data['quantity'];
        session()->put('cart', $cart);
    }

    return response()->json([
      'success'  => true,
      'quantity' => $data['quantity'],
    ]);
}
}
