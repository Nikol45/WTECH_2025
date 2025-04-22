<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartDeliveryController extends Controller
{
    // Zobrazí stránku
    public function index()
    {
        $cart            = Session::get('cart', []);
        $packages        = $cart['packages'] ?? [];
        $savedDelivery   = $cart['delivery'] ?? [];

        $totalValue = collect($packages)->sum(fn ($pkg) => $pkg['total_price'] ?? 0);

        $deliveryMethods = [
            'GLS_standard' => 'Doručenie na adresu cez GLS Standard',
            'GLS_express'  => 'Doručenie na adresu cez GLS Express',
            'personal'     => 'Osobný odber',
        ];

        $paymentMethods = [
            'card_online'      => 'Kartou online',
            'bank_transfer'    => 'Bankový prevod',
            'cash_on_delivery' => 'Dobierka',
        ];

        // Už uložené voľby
        $selectedDelivery = null; //$savedDelivery['deliveryMethod'] ?? null;
        $selectedPayment  = null; //$savedDelivery['paymentMethod'] ?? null;
        $showDeliveryWarning = collect($packages)
            ->contains(fn ($pkg) => $pkg['only_personal'] ?? false);

        // Ceny dopravy / dobierky (číselné)
        $deliveryValue = match ($selectedDelivery) {
            'GLS_standard' => 3.39,
            'GLS_express'  => 5.80,
            'personal'     => 0.00,
            default        => 0.00,
        };

        $codValue = $selectedPayment === 'cash_on_delivery' ? 0.90 : 0.00;

        $grandTotalValue = $totalValue + $deliveryValue + $codValue;

        // formátovanie cien
        $totalWithoutDelivery   = $this->formatPrice($totalValue);
        $deliveryPrice          = $this->formatPrice($deliveryValue);
        $codPrice               = $this->formatPrice($codValue);
        $grandTotal             = $this->formatPrice($grandTotalValue);

        $deliveryEta = match ($selectedDelivery) {
            'GLS_standard' => '23.4. – 25.4.',
            'GLS_express'  => '22.4. – 23.4.',
            default        => 'dd.mm. – dd.mm.',
        };

        $showPriceRow = $selectedDelivery && $selectedPayment;

        // Render
        return view('cart.delivery', compact(
            'packages',
            'deliveryMethods',  'paymentMethods',
            'selectedDelivery', 'selectedPayment',
            'deliveryPrice',    'deliveryEta', 'codPrice',
            'totalWithoutDelivery', 'grandTotal',
            'showPriceRow', 'showDeliveryWarning'
        ));
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);
        Session::put('cart.delivery', $validated);

        return redirect()->route('cart-summary.index');
    }

    public function update(Request $request)
    {
        $validated = $this->validateInput($request);
        Session::put('cart.delivery', $validated);

        return redirect()->route('cart-summary.index');
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'deliveryMethod' => 'required|string|in:GLS_standard,GLS_express,personal',
            'paymentMethod'  => 'required|string|in:card_online,bank_transfer,cash_on_delivery',
        ]);
    }

    private function formatPrice(float $value): string
    {
        return number_format($value, 2, ',', ' ') . ' €';
    }
}
