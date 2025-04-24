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
        // Dáta z košíka

        $items    = Session::get('cart.items', []);
        $form     = Session::get('cart.form', []);
        $delivery = Session::get('cart.delivery', [
            'methods'       => [],
            'prices'        => [],
            'deliveryMethod'=> null,
            'paymentMethod' => null,
            'cod'           => 0.0,
        ]);

        // Skupiny položiek podľa farmy
        $grouped = collect($items)->groupBy('farm_id');

        $farms = [];
        foreach ($grouped as $farmId => $farmItems) {
            $farm = Farm::find($farmId);
            if (!$farm) {
                continue; // neexistujúca farma
            }

            // Zoznam položiek farmy
            $list = $farmItems->map(fn ($item) => [
                'quantity' => $item['quantity'] ?? 1,
                'label'    => $item['label']    ?? '',
                'price'    => $this->formatPrice($item['price']),
            ])->toArray();

            // Doprava pre farmu
            $methodKey = $delivery['methods'][$farmId] ?? 'personal';

            $shippingLabel = $methodKey === 'personal' || !$farm->delivery_available
                ? 'Osobný odber'
                : ucfirst(str_replace('_', ' ', $methodKey));

            $shippingPrice = $methodKey === 'personal' || !$farm->delivery_available
                ? $this->formatPrice(0.0)
                : '- €';

            // Pridaj do výstupu pre túto farmu
            $farms[] = [
                'name'           => $farm->name,
                'items'          => $list,
                'shipping'       => $shippingLabel,
                'shipping_price' => $shippingPrice,
            ];
        }

        // Súhrn cien
        $withoutVat    = collect($items)->sum('price');
        $shippingTotal = collect($delivery['prices'])->max(); // kedže mame jedneho kuriera
        $codTotal      = $delivery['cod'] ?? 0.0;
        $total         = $withoutVat + $shippingTotal + $codTotal;

        $summary = [
            'without_vat' => $this->formatPrice($withoutVat),
            'shipping'    => $this->formatPrice($shippingTotal),
            'cod'         => $this->formatPrice($codTotal),
            'total'       => $this->formatPrice($total),
        ];

        // Informácie o zákazníkovi a adresách
        $customer        = [
            'name'  => $form['name']  ?? '',
            'email' => $form['email'] ?? '',
            'phone' => $form['phone'] ?? '',
        ];
        $billing         = $form['billing_address']  ?? [];
        $deliveryAddress = $form['delivery_address'] ?? null;
        $company         = $form['company']          ?? null;
        $note            = $form['note']             ?? null;

        // Dobierka?
        $isCod = ($delivery['paymentMethod'] ?? null) === 'cash_on_delivery';

        return view('cart.summary', compact(
            'farms',
            'summary',
            'customer',
            'billing',
            'deliveryAddress',
            'company',
            'note',
            'isCod'
        ));
    }

    public function store(Request $request)
    {
        $items    = Session::get('cart.items', []);
        $form     = Session::get('cart.form', []);
        $delivery = Session::get('cart.delivery', [
            'methods' => [],
            'prices'  => [],
            'cod'     => 0.0,
        ]);
        $paymentMethod = $delivery['paymentMethod'] ?? '';

        /** ---------- Adresy ---------- */
        $billingData = $form['billing_address'] ?? [];
        $billingAddr = Address::create([
            'street'        => $billingData['street']        ?? null,
            'street_number' => $billingData['street_number'] ?? null,
            'zip_code'      => $billingData['zip']           ?? null,
            'city'          => $billingData['city']          ?? null,
            'country'       => $billingData['country']       ?? null,
            'address_type'  => 'billing',
        ]);

        $deliveryAddr = null;
        if (!empty($form['delivery_address'])) {
            $delData     = $form['delivery_address'];
            $deliveryAddr = Address::create([
                'street'        => $delData['street']        ?? null,
                'street_number' => $delData['street_number'] ?? null,
                'zip_code'      => $delData['zip']           ?? null,
                'city'          => $delData['city']          ?? null,
                'country'       => $delData['country']       ?? null,
                'address_type'  => 'delivery',
            ]);
        }

        /** ---------- Spoločnosť ---------- */
        $companyModel = null;
        if (!empty($form['company'])) {
            $compData = $form['company'];
            $companyModel = Company::create([
                'name'   => $compData['name'] ?? null,
                'ico'    => $compData['ico']  ?? null,
                'ic_dph' => $compData['vat']  ?? null,
            ]);
        }

        /** ---------- Objednávka ---------- */
        $order = Order::create([
            'user_id'             => Auth::id(),
            'billing_address_id'  => $billingAddr->id,
            'delivery_address_id' => $deliveryAddr->id  ?? null,
            'company_id'          => $companyModel->id  ?? null,
            'payment_type'        => $paymentMethod,
            'delivery_type'       => $form['delivery_type'] ?? null,
            'note'                => $form['note'] ?? null,
            'total_price'         => collect($items)->sum('price')
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
                'expected_delivery_date' => Carbon::now(), // prispôsobiť podľa logiky farmy
                'status'                 => 'pending',
            ]);

            foreach ($farmItems as $item) {
                $package->items()->create([
                    'farm_product_id'    => $item['farm_product_id'] ?? $item['id'],
                    'quantity'           => $item['quantity'] ?? 1,
                    'price_when_ordered' => $item['price'],
                ]);
            }
        }

        /** ---------- Vyčistenie košíka ---------- */
        Session::forget('cart');

        return redirect()
            ->route('orders.index')
            ->with('success', 'Objednávka bola úspešne odoslaná.');
    }

    private function formatPrice(float $value): string
    {
        return number_format($value, 2, ',', ' ') . ' €';
    }
}
