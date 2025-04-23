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
        $delivery = Session::get('cart.delivery', ['methods' => [], 'prices' => []]);

        // group položiek podľa farmy
        $grouped = collect($items)->groupBy('farm_id');

        $farms = [];
        foreach ($grouped as $farmId => $farmItems) {
            $farm = Farm::find($farmId);
            $list = $farmItems->map(function ($item) {
                return [
                    'label' => $item['label'] ?? '',
                    'price' => number_format($item['price'], 2) . ' €',
                ];
            })->toArray();

            $method = $delivery['methods'][$farmId] ?? '';
            $price  = $delivery['prices'][$farmId]  ?? 0;

            $farms[] = [
                'name'           => $farm->name ?? '',
                'items'          => $list,
                'shipping'       => $method,
                'shipping_price' => number_format($price, 2) . ' €',
            ];
        }

        // Výpočet súm
        $withoutVat    = collect($items)->sum('price');
        $shippingTotal = collect($delivery['prices'])->sum();
        $total         = $withoutVat + $shippingTotal;

        $summary = [
            'without_vat' => number_format($withoutVat, 2) . ' €',
            'shipping'    => number_format($shippingTotal, 2) . ' €',
            'total'       => number_format($total, 2) . ' €',
        ];

        // Príprava údajov z formulára
        $customer        = [
            'name'  => $form['name']  ?? '',
            'email' => $form['email'] ?? '',
            'phone' => $form['phone'] ?? '',
        ];
        // Adresy a spoločnosť - môžu byť null, ak nevyplnené
        $billing         = $form['billing_address']    ?? [];
        $deliveryAddress = $form['delivery_address']  ?? null;
        $company         = $form['company']           ?? null;
        $note            = $form['note']              ?? null;

        return view('cart.summary', compact(
            'farms',
            'summary',
            'customer',
            'billing',
            'deliveryAddress',
            'company',
            'note'
        ));
    }

    /**
     * Uloží finálnu objednávku vrátane adries a spoločnosti.
     */
    public function store(Request $request)
    {
        $items    = Session::get('cart.items', []);
        $form     = Session::get('cart.form', []);
        $delivery = Session::get('cart.delivery', ['methods' => [], 'prices' => []]);
        $payment  = Session::get('cart.paymentMethod', '');

        // Vytvorenie adries z formulára
        $billingData = $form['billing_address'] ?? [];
        $billingAddr = Address::create([
            'street'        => $billingData['street'] ?? null,
            'street_number' => $billingData['street_number'] ?? null,
            'zip_code'      => $billingData['zip'] ?? null,
            'city'          => $billingData['city'] ?? null,
            'country'       => $billingData['country'] ?? null,
            'address_type'  => 'billing',
        ]);

        $deliveryAddr = null;
        if (!empty($form['delivery_address'])) {
            $delData = $form['delivery_address'];
            $deliveryAddr = Address::create([
                'street'        => $delData['street'] ?? null,
                'street_number' => $delData['street_number'] ?? null,
                'zip_code'      => $delData['zip'] ?? null,
                'city'          => $delData['city'] ?? null,
                'country'       => $delData['country'] ?? null,
                'address_type'  => 'delivery',
            ]);
        }

        // Vytvorenie spoločnosti (ak zadaná)
        $companyModel = null;
        if (!empty($form['company'])) {
            $compData = $form['company'];
            $companyModel = Company::create([
                'name'      => $compData['name'] ?? null,
                'ico'       => $compData['ico'] ?? null,
                'ic_dph'    => $compData['vat'] ?? null,
            ]);
        }

        // Vytvor objednávku cez Eloquent
        $order = Order::create([
            'user_id'              => Auth::id(),
            'billing_address_id'   => $billingAddr->id,
            'delivery_address_id'  => $deliveryAddr->id ?? null,
            'company_id'           => $companyModel->id ?? null,
            'payment_type'         => $payment,
            'delivery_type'        => $form['delivery_type'] ?? null,
            'note'                 => $form['note'] ?? null,
            'total_price'          => collect($items)->sum('price') + collect($delivery['prices'])->sum(),
        ]);

        // Skup položky podľa farmy a vytvor balíky s položkami
        $grouped = collect($items)->groupBy('farm_id');
        foreach ($grouped as $farmId => $farmItems) {
            $price   = $delivery['prices'][$farmId] ?? 0;
            $package = $order->packages()->create([
                'farm_id'                 => $farmId,
                'price'                   => $price,
                'expected_delivery_date'  => Carbon::now()->addDays(0),
                'status'                  => 'pending',
            ]);

            foreach ($farmItems as $item) {
                $package->items()->create([
                    'farm_product_id'    => $item['farm_product_id'] ?? $item['id'],
                    'quantity'           => $item['quantity'] ?? 1,
                    'price_when_ordered' => $item['price'],
                ]);
            }
        }

        // Vyčisti košík zo session
        Session::forget('cart');

        return redirect()->route('orders.index')
            ->with('success', 'Objednávka bola úspešne odoslaná.');
    }
}
