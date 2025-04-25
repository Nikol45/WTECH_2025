<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Session;

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
        $cartData = $this->buildCartFormData(
            $this->validateInput($request)
        );

        Session::put('cart.form', $cartData);

        return redirect()->route('cart-delivery.index');
    }

    // Aktualizácia údajov (pri návrate späť)
    public function update(Request $request)
    {
        $cartData = $this->buildCartFormData(
            $this->validateInput($request)
        );

        Session::put('cart.form', $cartData);

        return redirect()->route('cart-delivery.index');
    }

    // ----------------- SÚKROMNÉ METÓDY -----------------

    /**
     * Overí vstupné dáta z formulára.
     */
    private function validateInput(Request $request): array
    {
        $differentAddress = boolval($request->input('different_address'));
        $isCompany = boolval($request->input('company_check'));

        return $request->validate([
            // Základné údaje
            'first_name'  => 'required|string|max:255',
            'last_name'   => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'       => 'required|string|max:30',

            // Fakturačná adresa
            'bill_street'        => 'required|string|max:255',
            'bill_street_number' => 'required|string|max:30',
            'bill_zip'           => 'required|string|max:20',
            'bill_city'          => 'required|string|max:255',
            'bill_country'       => 'required|string|max:100',

            // Dodacia adresa
            'del_street'        => [$differentAddress ? 'required' : 'nullable', 'string', 'max:255'],
            'del_street_number' => [$differentAddress ? 'required' : 'nullable', 'string', 'max:30'],
            'del_zip'           => [$differentAddress ? 'required' : 'nullable', 'string', 'max:20'],
            'del_city'          => [$differentAddress ? 'required' : 'nullable', 'string', 'max:255'],
            'del_country'       => [$differentAddress ? 'required' : 'nullable', 'string', 'max:100'],

            // Firemné údaje
            'company_name' => [$isCompany ? 'required' : 'nullable', 'string', 'max:255'],
            'ico'          => [$isCompany ? 'required' : 'nullable', 'string', 'max:20'],
            'vat'          => 'nullable|string|max:20',

            // Poznámka
            'note'         => 'nullable|string|max:1000',
        ]);
    }

    /**
     * Z validovaných polí zloží štruktúru cart.form.
     */
    private function buildCartFormData(array $v): array
    {
        $deliveryAddress = [
            'street'        => $v['del_street']        ?? $v['bill_street'],
            'street_number' => $v['del_street_number'] ?? $v['bill_street_number'],
            'zip'           => $v['del_zip']           ?? $v['bill_zip'],
            'city'          => $v['del_city']          ?? $v['bill_city'],
            'country'       => $v['del_country']       ?? $v['bill_country'],
        ];

        return [
            'name'  => trim($v['first_name'] . ' ' . $v['last_name']),
            'first_name' => $v['first_name'],
            'last_name'  => $v['last_name'],
            'email' => $v['email'],
            'phone' => $v['phone'],

            'billing_address' => [
                'street'        => $v['bill_street'],
                'street_number' => $v['bill_street_number'],
                'zip'           => $v['bill_zip'],
                'city'          => $v['bill_city'],
                'country'       => $v['bill_country'],
            ],

            'delivery_address' => $deliveryAddress,

            'company' => [
                'ico'  => $v['ico'],
                'name' => $v['company_name'],
                'vat'  => $v['vat'],
            ],

            'note' => $v['note'],
        ];
    }
}
