<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartDeliveryController extends Controller
{
    // Definície metód doručenia a platby
    private const PRICE_GLS_STANDARD = 3.39;
    private const PRICE_GLS_EXPRESS  = 5.80;
    private const PRICE_PERSONAL     = 0.00;
    public const COD_PRICE          = 0.85;

    public const DELIVERY_METHODS = [
        'GLS_standard' => [
            'label' => 'Doručenie na adresu cez GLS Standard',
            'price' => self::PRICE_GLS_STANDARD,
            'eta'   => '23.4. – 25.4.',
        ],
        'GLS_express' => [
            'label' => 'Doručenie na adresu cez GLS Express',
            'price' => self::PRICE_GLS_EXPRESS,
            'eta'   => '22.4. – 23.4.',
        ],
        'personal' => [
            'label' => 'Osobný odber',
            'price' => self::PRICE_PERSONAL,
            'eta'   => 'dd.mm. – dd.mm.',
        ],
    ];

    private const PAYMENT_METHODS = [
        'card_online'      => 'Kartou online',
        'bank_transfer'    => 'Bankový prevod',
        'cash_on_delivery' => 'Dobierka',
        'cash_on_pickup'   => 'Platba v hotovosti',
    ];

    public function index()
    {
        $cart            = Session::get('cart', []);
        $packages        = $cart['packages'] ?? [];
        $savedDelivery   = $cart['delivery'] ?? [];

        $selectedDelivery = $savedDelivery['deliveryMethod'] ?? null;
        $selectedPayment  = $savedDelivery['paymentMethod'] ?? null;

        $totalValue = collect($packages)->sum(fn($pkg) => $pkg['total_price'] ?? 0);
        $deliveryValue = $this->getDeliveryPrice($selectedDelivery);
        $codValue      = $this->getCodPrice($selectedPayment);
        $grandTotal    = $totalValue + $deliveryValue + $codValue;

        $showDeliveryWarning = collect($packages)
            ->contains(fn($pkg) => $pkg['only_personal'] ?? false);

        $viewData = [
            'packages'              => $packages,
            'deliveryMethods'       => $this->getDeliveryLabels(),
            'paymentMethods'        => self::PAYMENT_METHODS,
            'selectedDelivery'      => $selectedDelivery,
            'selectedPayment'       => $selectedPayment,
            'deliveryPrice'         => $this->formatPrice($deliveryValue),
            'codPrice'              => $this->formatPrice($codValue),
            'totalWithoutDelivery'  => $this->formatPrice($totalValue),
            'grandTotal'            => $this->formatPrice($grandTotal),
            'deliveryEta'           => $this->getDeliveryEta($selectedDelivery),
            'showPriceRow'          => $selectedDelivery && $selectedPayment,
            'showDeliveryWarning'   => $showDeliveryWarning,
        ];

        return view('cart.delivery', $viewData);
    }

    public function store(Request $request)
    {
        $validated = $this->validateInput($request);

        $deliveryMethod = $validated['deliveryMethod'];
        $paymentMethod  = $validated['paymentMethod'];
        $packages       = Session::get('cart.packages', []);

        $deliveryValue = $this->getDeliveryPrice($deliveryMethod);
        $codValue      = $this->getCodPrice($paymentMethod);

        $delivery = [
            'deliveryMethod' => $deliveryMethod,
            'paymentMethod'  => $paymentMethod,
            'cod'            => $codValue,
            'methods'        => [],
            'prices'         => [],
        ];

        foreach ($packages as $pkg) {
            $farmId = $pkg['farm_id'];
            $delivery['methods'][$farmId] = $deliveryMethod;
            $delivery['prices'][$farmId]  = $deliveryValue;
        }

        Session::put('cart.delivery', $delivery);

        return redirect()->route('cart-summary.index');
    }

    public function update(Request $request)
    {
        return $this->store($request);
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'deliveryMethod' => 'required|string|in:' . implode(',', array_keys(self::DELIVERY_METHODS)),
            'paymentMethod'  => 'required|string|in:' . implode(',', array_keys(self::PAYMENT_METHODS)),
        ]);
    }

    private function getDeliveryLabels(): array
    {
        return collect(self::DELIVERY_METHODS)->mapWithKeys(
            fn($data, $key) => [$key => $data['label']]
        )->toArray();
    }

    private function getDeliveryPrice(?string $method): float
    {
        return self::DELIVERY_METHODS[$method]['price'] ?? 0.00;
    }

    private function getDeliveryEta(?string $method): string
    {
        return self::DELIVERY_METHODS[$method]['eta'] ?? 'dd.mm. – dd.mm.';
    }

    private function getCodPrice(?string $payment): float
    {
        return $payment === 'cash_on_delivery' ? self::COD_PRICE : 0.00;
    }

    private function formatPrice(float $value): string
    {
        return number_format($value, 2, ',', ' ') . ' €';
    }
}
