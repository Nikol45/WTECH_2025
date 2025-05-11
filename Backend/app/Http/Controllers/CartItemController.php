<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\FarmProduct;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;

class CartItemController extends Controller
{
    public function index(Request $request) {
        if (Auth::check()) {  //prihlásený používateľ
            $dbItems = CartItem::with(['farm_product.product.image','farm_product.farm'])
                ->where('user_id', Auth::id())
                ->get();

            $cart = $dbItems->map(function($ci) {
                $fp = $ci->farm_product;
                return [
                    'fp' => $fp,
                    'quantity' => $ci->quantity,
                ];
            });

        }
        else {  //neprihlásený používateľ
            $cart = session('cart.items', []);

            if (empty($cart)) {
                $cart = collect();
            }
            else {
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

        $cartByFarm = $cart ? $cart->groupBy(fn($item) => $item['fp']->farm->id) : null;

        return view('cart.index', ['cart'=>$cart, 'cartByFarm' => $cartByFarm]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request) {
        $data = $request->validate([
            'farm_product_id' => 'required|int|exists:farm_products,id',
            'quantity' => 'required|numeric|min:1',
        ]);

        $fp = FarmProduct::findOrFail($data['farm_product_id']);

        if (Auth::check()) {
            $user = Auth::user();

            $item = CartItem::firstOrNew([
                'user_id' => $user->id,
                'farm_product_id' => $fp->id,
            ]);

            $item->quantity = $item->exists ? $item->quantity + $data['quantity'] : $data['quantity'];

            $item->save();

            $cartItems = CartItem::with('farm_product.product', 'farm_product.farm')
                ->where('user_id', $user->id)
                ->get();

            Session::put('cart.items', $cartItems->map(function ($item) {
                return [
                    'farm_product_id' => $item->farm_product_id,
                    'quantity' => $item->quantity,
                    'label' => $item->farm_product->product->name ?? 'Produkt',
                    'price' => $item->farm_product->price_sell_quantity,
                    'farm_id' => $item->farm_product->farm_id,
                ];
            })->toArray());

        }
        else {
            $cart = session()->get('cart.items', []);
            if (isset($cart[$fp->id])) {
                $cart[$fp->id] += $data['quantity'];
            }
            else {
                $cart[$fp->id] = $data['quantity'];
            }
            session()->put('cart.items', $cart);
        }


        return back()->with('success', 'Produkt pridaný do košíka.');
    }

    public function show(CartItem $cartItem)
    {
        //
    }

    public function edit(CartItem $cartItem)
    {
        //
    }


    public function destroy(Request $request) {
        $ids = $request->input('items', []);

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->whereIn('farm_product_id', $ids)
                ->delete();
        }
        else {
            $cart = session()->get('cart.items', []);
            foreach ($ids as $id) {
                unset($cart[$id]);
            }
            session(['cart.items' => $cart]);
        }

        return redirect()
            ->route('cart-items.index')
            ->with('success','Vybrané položky boli odstránené.');
    }

    public function update(Request $request, $farmProductId) {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (Auth::check()) {
            $item = CartItem::firstOrNew([
            'user_id' => Auth::id(),
            'farm_product_id' => $farmProductId,
            ]);
            $item->quantity = $data['quantity'];
            $item->save();
        }
        else {
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
