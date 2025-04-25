<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\Farm;
use App\Models\Address;
use App\Models\Company;
use Carbon\Carbon;

class CartSummaryController extends Controller
{
    public function index()
    {
        $items    = Session::get('cart.items', []);
        $form     = Session::get('cart.form', []);
        $delivery = Session::get('cart.delivery', [
            'methods'       => [],
            'prices'        => [],
            'deliveryMethod'=> null,
            'paymentMethod' => null,
            'cod'           => 0.0,
        ]);

        $grouped = collect($items)->groupBy('farm_id');
        $farms = [];

        foreach ($grouped as $farmId => $farmItems) {
            $farm = Farm::find($farmId);
            if (!$farm) continue;

            $list = $farmItems->map(fn ($item) => [
                'quantity' => $item['quantity'] ?? 1,
                'label'    => $item['label']    ?? '',
                'price'    => $this->formatPrice($item['price']),
            ])->toArray();

            $totalPrice = collect($farmItems)->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
            $methodKey = $delivery['methods'][$farmId] ?? 'personal';

            $shippingLabel = $methodKey === 'personal' || !$farm->delivery_available
                ? 'Osobný odber'
                : ucfirst(str_replace('_', ' ', $methodKey));

            $shippingPrice = $methodKey === 'personal' || !$farm->delivery_available
                ? $this->formatPrice(0.0)
                : '- €';

            $farms[] = [
                'name'           => $farm->name,
                'items'          => $list,
                'shipping'       => $shippingLabel,
                'shipping_price' => $shippingPrice,
            ];
        }

        $withoutVat    = collect($items)->sum('price');
        $shippingTotal = collect($delivery['prices'])->max();
        $codTotal      = $delivery['cod'] ?? 0.0;
        $total         = $withoutVat + $shippingTotal + $codTotal;

        $summary = [
            'without_vat' => $this->formatPrice($withoutVat),
            'shipping'    => $this->formatPrice($shippingTotal),
            'cod'         => $this->formatPrice($codTotal),
            'total'       => $this->formatPrice($total),
        ];

        $customer = [
            'name'  => $form['name']  ?? '',
            'email' => $form['email'] ?? '',
            'phone' => $form['phone'] ?? '',
        ];

        $billing         = $form['billing_address']  ?? [];
        $deliveryAddress = $form['delivery_address'] ?? null;
        $company         = $form['company']          ?? null;
        $note            = $form['note']             ?? null;
        $isCod           = ($delivery['paymentMethod'] ?? null) === 'cash_on_delivery';

        return view('cart.summary', compact(
            'farms', 'summary', 'customer',
            'billing', 'deliveryAddress', 'company',
            'note', 'isCod'
        ));
    }

    public function store(Request $request)
    {
        $items    = Session::get('cart.items', []);
        $form     = Session::get('cart.form', []);
        $delivery = Session::get('cart.delivery', [
            'methods' => [], 'prices' => [], 'cod' => 0.0
        ]);

        /** ---------- Adresy ---------- */
        $billingData = $form['billing_address'] ?? [];

        $billingAddr = Address::create([
            'street'        => $billingData['street'] ?? '',
            'street_number' => $billingData['street_number'] ?? '',
            'zip_code'      => $billingData['zip'] ?? '',
            'city'          => $billingData['city'] ?? '',
            'country'       => $billingData['country'] ?? '',
            'address_type'  => 'billing',
        ]);

    // Ak nie je zadaná doručovacia adresa → použije sa billing adresa
        $deliveryAddr = null;
        if (!empty($form['delivery_address'])) {
            $delData = $form['delivery_address'];
            $deliveryAddr = Address::create([
                'street'        => $delData['street'] ?? '',
                'street_number' => $delData['street_number'] ?? '',
                'zip_code'      => $delData['zip'] ?? '',
                'city'          => $delData['city'] ?? '',
                'country'       => $delData['country'] ?? '',
                'address_type'  => 'delivery',
            ]);
        } else {
            $deliveryAddr = $billingAddr;
        }

        /** ---------- Spoločnosť ---------- */
        $companyModel = null;
        if (!empty($form['company']) && !empty($form['company']['name'])) {
            $compData = $form['company'];

            $companyModel = Company::create([
                'name'   => $compData['name'],
                'ico'    => $compData['ico']  ?? '',
                'ic_dph' => $compData['vat']  ?? '',
            ]);
        }

        // Mapovanie fancy názvov na DB enum hodnoty
        $paymentMap = [
            'card_online'      => 'online',
            'bank_transfer'    => 'transfer',
            'cash_on_delivery' => 'cash',
            'cash_on_pickup'   => 'cash',
        ];

        $rawPayment = $delivery['paymentMethod'] ?? 'cash';
        $mappedPayment = $paymentMap[$rawPayment] ?? 'cash';


        /** ---------- Objednávka ---------- */
        $order = Order::create([
            'user_id'             => Auth::id(),
            'billing_address_id'  => $billingAddr->id,
            'delivery_address_id' => $deliveryAddr?->id,
            'company_id'          => $companyModel?->id,
            'payment_type'        => $mappedPayment,
            'delivery_type'       => $form['delivery_type'] ?? 'in_person',
            'note'                => $form['note'] ?? '',
            'total_price'         => collect($items)->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1))
                + collect($delivery['prices'])->sum()
                + ($delivery['cod'] ?? 0.0),
        ]);

        /** ---------- Balíky a položky ---------- */
        $grouped = collect($items)->groupBy('farm_id');

        foreach ($grouped as $farmId => $farmItems) {
            $pricePerFarm = $delivery['prices'][$farmId] ?? 0.0;

            $package = $order->packages()->create([
                'farm_id'                => $farmId,
                'price'                  => $pricePerFarm,
                'expected_delivery_date' => Carbon::now(),
                'status'                 => 'pending',
            ]);

            foreach ($farmItems as $item) {
                $package->order_items()->create([
                    'farm_product_id'    => $item['farm_product_id'] ?? $item['id'],
                    'quantity'           => $item['quantity'] ?? 1,
                    'price_when_ordered' => $item['price'],
                ]);
            }
        }

        Session::forget('cart');

        return redirect()
            ->route('cart.confirmation')
            ->with('success', 'Objednávka bola úspešne odoslaná.');
    }

    public function confirmation()
    {
        return view('cart.confirmation');
    }

    private function formatPrice(float|null $value): string
    {
        return number_format($value ?? 0.0, 2, ',', ' ') . ' €';
    }
}
