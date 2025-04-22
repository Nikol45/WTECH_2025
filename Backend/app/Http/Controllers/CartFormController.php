<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartFormController extends Controller
{
    // Zobrazí formulár s predvyplnenými údajmi zo session
    public function index()
    {
        $data = session('cart.form', []);
        return view('cart.form', compact('data'));
    }

    // Prvé uloženie údajov
    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        session(['cart.form' => $validated]);

        return redirect()->route('cart-delivery.index');
    }

    // Aktualizácia údajov (ak sa používateľ vráti späť)
    public function update(Request $request)
    {
        $validated = $this->validateInput($request);
        session(['cart.form' => $validated]);

        return redirect()->route('cart-delivery.index');
    }

    // Validácia vstupov
    private function validateInput(Request $request): array
    {
        return $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email',
            'phone'        => 'required|string|max:30',
            'street'       => 'required|string|max:255',
            'city'         => 'required|string|max:255',
            'postal_code'  => 'required|string|max:20',
            'country'      => 'required|string|max:100',
        ]);
    }
}
