<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\CartItem;
use App\Models\FarmProduct;

class CartDeliveryController extends Controller
{
    // Fixné ceny
    private const PRICE_GLS_STANDARD = 3.39;
    private const PRICE_GLS_EXPRESS  = 5.80;
    private const PRICE_PERSONAL     = 0.00;
    public  const COD_PRICE          = 0.85;

    // Dynamicky plnené podľa balíkov
    public static array $DELIVERY_METHODS = [];

    private const PAYMENT_METHODS = [
        'card_online'      => 'Kartou online',
        'bank_transfer'    => 'Bankový prevod',
        'cash_on_delivery' => 'Dobierka',
        'cash_on_pickup'   => 'Platba v hotovosti',
    ];

    public function index(): View {
        if (Auth::check()) {
            /* Prihlásený používateľ – košík z DB */
            $userId = Auth::id();

            $cartItems = CartItem::with('farm_product.farm')
                ->where('user_id', $userId)
                ->get();

            $packages = $cartItems
                ->groupBy(fn($ci) => $ci->farm_product->farm->id)
                ->map(function ($items, $farmId) {
                    $farm = $items->first()->farm_product->farm;
                    $expectedDate = now()
                        ->addDays($farm->avg_delivery_time ?? 2)
                        ->toDateString();

                    $products = $items->map(function ($ci) {
                        $fp = $ci->farm_product;
                        $unitPrice = $fp->discount_percentage ? $fp->price_sell_quantity * (100 - $fp->discount_percentage) / 100 : $fp->price_sell_quantity;

                        return [
                            'id' => $fp->id,
                            'image' => $fp->product->image->path,
                            'name' => $fp->product->name,
                            'price' => number_format($unitPrice, 2, ',', ' ') . ' €',
                            'quantity' => $ci->quantity,
                        ];
                    })->toArray();

                    return [
                        'farm_id' => $farmId,
                        'farm_name' => $farm->name,
                        'only_personal' => ! $farm->delivery_available,
                        'expected_delivery_date' => $expectedDate,
                        'total_price' => $items->sum(fn($ci) => $ci->quantity * ($ci->farm_product->discount_percentage ? $ci->farm_product->price_sell_quantity * (100 - $ci->farm_product->discount_percentage) / 100: $ci->farm_product->price_sell_quantity)),
                        'products' => $products,
                    ];
                })
                ->values()
                ->toArray();

            $savedDelivery = Session::get('cart.delivery', []);
        }
        else {
            /* Hosť – košík v session */
            $sessionCart = Session::get('cart.items', []);
            $fps = FarmProduct::with('farm','product.image')
                ->whereIn('id', array_keys($sessionCart))
                ->get();

            $cartItems = $fps->map(function($fp) use($sessionCart) {
                return [
                    'farm_product' => $fp,
                    'quantity' => $sessionCart[$fp->id] ?? 0,
                ];
            });

            $packages = $cartItems
                ->groupBy(fn($ci) => $ci['farm_product']->farm->id)
                ->map(function($items, $farmId) {
                    $farm = $items->first()['farm_product']->farm;
                    $expectedDate = now()
                        ->addDays($farm->avg_delivery_time ?? 2)
                        ->toDateString();

                    $products = $items->map(function($ci) {
                        $fp = $ci['farm_product'];
                        $unitPrice = $fp->discount_percentage ? $fp->price_sell_quantity * (100 - $fp->discount_percentage)/100 : $fp->price_sell_quantity;

                        return [
                            'id' => $fp->id,
                            'image' => $fp->product->image->path,
                            'name' => $fp->product->name,
                            'price' => number_format($unitPrice,2,',',' ') . ' €',
                            'quantity' => $ci['quantity'],
                        ];
                    })->toArray();

                    $totalPrice = $items->sum(fn($ci) =>
                        ($ci['quantity']) * ($ci['farm_product']->discount_percentage ? $ci['farm_product']->price_sell_quantity * (100 - $ci['farm_product']->discount_percentage)/100 : $ci['farm_product']->price_sell_quantity)
                    );

                    return [
                        'farm_id' => $farmId,
                        'farm_name' => $farm->name,
                        'only_personal' => ! $farm->delivery_available,
                        'expected_delivery_date' => $expectedDate,
                        'total_price' => $totalPrice,
                        'products' => $products,
                    ];
                })
            ->values()
            ->toArray();
        }

        self::bootDeliveryMethods($packages);

        $selectedDelivery = $savedDelivery['deliveryMethod'] ?? null;
        $selectedPayment  = $savedDelivery['paymentMethod']  ?? null;

        $totalValue    = collect($packages)->sum(fn($p) => $p['total_price'] ?? 0);
        $deliveryValue = $this->getDeliveryPrice($selectedDelivery);
        $codValue      = $this->getCodPrice($selectedPayment);
        $grandTotal    = $totalValue + $deliveryValue + $codValue;

        $showDeliveryWarning = collect($packages)->contains(
            fn($p) => $p['only_personal'] ?? false
        );

        $onlyPersonalAvailable = collect($packages)->every(fn($p) => $p['only_personal'] ?? false);

        return view('cart.delivery', [
            'packages' => $packages,
            'deliveryMethods' => self::$DELIVERY_METHODS,
            'paymentMethods' => self::PAYMENT_METHODS,
            'selectedDelivery' => $selectedDelivery,
            'selectedPayment' => $selectedPayment,
            'deliveryPrice' => $this->formatPrice($deliveryValue),
            'codPrice' => $this->formatPrice($codValue),
            'totalWithoutDelivery' => $this->formatPrice($totalValue),
            'grandTotal' => $this->formatPrice($grandTotal),
            'deliveryEta' => $this->getDeliveryEta($selectedDelivery),
            'showPriceRow' => $selectedDelivery && $selectedPayment,
            'showDeliveryWarning' => $showDeliveryWarning,
            'onlyPersonalAvailable' => $onlyPersonalAvailable,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $userId = Auth::id();

            $cartItems = CartItem::with('farm_product.farm')
                ->where('user_id', $userId)
                ->get();

            $packages = $cartItems->groupBy(fn($item) => $item->farm_product->farm->id)
                ->map(function ($items, $farmId) {
                    $farm = $items->first()->farm_product->farm;
                    $expectedDate = now()->addDays($farm->avg_delivery_time ?? 2)->toDateString();

                    return [
                        'farm_id'                => $farmId,
                        'farm_name'              => $farm->name,
                        'only_personal'          => !$farm->delivery_available,
                        'expected_delivery_date' => $expectedDate,
                        'total_price'            => $items->sum(fn($item) =>
                            $item->farm_product->price_sell_quantity * $item->quantity
                        ),
                    ];
                })
                ->values()
                ->toArray();
        } else {
            $sessionCart = Session::get('cart.items', []);
        $fps = FarmProduct::with('farm')
             ->whereIn('id', array_keys($sessionCart))
             ->get();

        $cartItems = $fps->map(fn($fp) => [
            'farm_product' => $fp,
            'quantity'     => $sessionCart[$fp->id] ?? 0,
        ]);

        $packages = $cartItems
            ->groupBy(fn($ci) => $ci['farm_product']->farm->id)
            ->map(function($items, $farmId) {
                $farm = $items->first()['farm_product']->farm;
                $expectedDate = now()
                    ->addDays($farm->avg_delivery_time ?? 2)
                    ->toDateString();

                $products = $items->map(fn($ci) => [
                    'id'       => $ci['farm_product']->id,
                    'image'    => $ci['farm_product']->product->image->path,
                    'name'     => $ci['farm_product']->product->name,
                    'price'    => number_format(
                        ($unit = $ci['farm_product']->discount_percentage
                            ? $ci['farm_product']->price_sell_quantity * (100 - $ci['farm_product']->discount_percentage)/100
                            : $ci['farm_product']->price_sell_quantity
                        ), 2, ',', ' '
                    ) . ' €',
                    'quantity' => $ci['quantity'],
                ])->toArray();

                $total = $items->sum(fn($ci) =>
                    $ci['quantity'] * (
                      $ci['farm_product']->discount_percentage
                        ? $ci['farm_product']->price_sell_quantity * (100 - $ci['farm_product']->discount_percentage)/100
                        : $ci['farm_product']->price_sell_quantity
                    )
                );

                return [
                    'farm_id'                 => $farmId,
                    'farm_name'               => $farm->name,
                    'only_personal'           => ! $farm->delivery_available,
                    'expected_delivery_date'  => $expectedDate,
                    'total_price'             => round($total, 2),
                    'products'                => $products,
                ];
            })
            ->values()
            ->toArray();
        }

        self::bootDeliveryMethods($packages); // musí byť pred validáciou

        $validated = $this->validateInput($request);
        $deliveryMethod = $validated['deliveryMethod'] ?? (collect($packages)->every(fn($p) => $p['only_personal']) ? 'personal' : null);
        $paymentMethod  = $validated['paymentMethod'];

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

    public function update(Request $request): RedirectResponse
    {
        return $this->store($request);
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'deliveryMethod' => 'required|string|in:' . implode(',', array_keys(self::$DELIVERY_METHODS)),
            'paymentMethod'  => 'required|string|in:' . implode(',', array_keys(self::PAYMENT_METHODS)),
        ]);
    }

    private function getDeliveryPrice(?string $method): float
    {
        return self::$DELIVERY_METHODS[$method]['price'] ?? 0.00;
    }

    private function getDeliveryEta(?string $method): string
    {
        return self::$DELIVERY_METHODS[$method]['eta'] ?? 'dd.mm. – dd.mm.';
    }

    private function getCodPrice(?string $payment): float
    {
        return $payment === 'cash_on_delivery' ? self::COD_PRICE : 0.00;
    }

    private function formatPrice(float $value): string
    {
        return number_format($value, 2, ',', ' ') . ' €';
    }

    public static function bootDeliveryMethods(array $packages): void
    {
        $baseDate = collect($packages)
            ->pluck('expected_delivery_date')
            ->filter()
            ->map(static function ($d) {
                try {
                    return Carbon::parse($d);
                } catch (\Throwable $e) {
                    return null;
                }
            })
            ->filter()
            ->sort()
            ->first();

        if (!$baseDate) {
            $etaExpress  = 'dd.mm. – dd.mm.';
            $etaStandard = 'dd.mm. – dd.mm.';
        } else {
            $etaExpress  = self::formatEtaRange($baseDate, 0, 2);
            $etaStandard = self::formatEtaRange($baseDate, 2, 3);
        }

        self::$DELIVERY_METHODS = [
            'GLS_standard' => [
                'label' => 'Doručenie na adresu cez GLS standard',
                'price' => self::PRICE_GLS_STANDARD,
                'eta'   => $etaStandard,
            ],
            'GLS_express' => [
                'label' => 'Doručenie na adresu cez GLS express',
                'price' => self::PRICE_GLS_EXPRESS,
                'eta'   => $etaExpress,
            ],
            'personal' => [
                'label' => 'Osobný odber',
                'price' => self::PRICE_PERSONAL,
                'eta'   => 'dd.mm. – dd.mm.',
            ],
        ];
    }

    private static function formatEtaRange(Carbon $start, int $addDays, int $rangeDays): string
    {
        $from = $start->copy()->addDays($addDays)->format('d.m.');
        $to   = $start->copy()->addDays($addDays + $rangeDays)->format('d.m.');
        return "$from – $to";
    }
}
