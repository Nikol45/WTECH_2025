<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\FarmProductController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\CartFormController;
use App\Http\Controllers\CartDeliveryController;
use App\Http\Controllers\CartSummaryController;
use App\Http\Controllers\HomeController;

use App\Models\Farm;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'homepage'])->name('homepage');
Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');
Route::get('/cart-items', [CartItemController::class, 'index'])->name('cart-items.index');
Route::post('cart-items', [CartItemController::class,'store'])->name('cart.store');
Route::delete('/cart-items',[CartItemController::class,'destroy'])->name('cart.destroy');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{farm_product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/profile-history', [ProfileHistoryController::class, 'index'])->name('profile-history.index');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile-reviews', [ProfileReviewsController::class, 'index'])->name('profile-reviews.index');
Route::patch('cart-items/{farm_product}',
    [CartItemController::class,'update'])
  ->name('cart.updateQuantity');

Route::resource('addresses', AddressController::class);
Route::resource('articles', ArticleController::class);
Route::resource('cart-items', CartItemController::class);
Route::resource('companies', CompanyController::class);
Route::resource('farms', FarmController::class);
Route::resource('farm-products', FarmProductController::class);
Route::resource('favourites', FavouriteController::class);
Route::resource('images', ImageController::class);
Route::resource('orders', OrderController::class);
Route::resource('order-items', OrderItemController::class);
Route::resource('packages', PackageController::class);
Route::resource('reviews', ReviewController::class);
Route::resource('cart-form',  CartFormController::class);
Route::resource('cart-delivery',  CartDeliveryController::class);
Route::resource('cart-summary',  CartSummaryController::class);

Route::view('/obchodne-podmienky', 'static.terms')->name('terms');


//testers
Route::get('/cart-tester', function () {
    $farmIds = [1, 2, 3];
    $farms = Farm::whereIn('id', $farmIds)->get()->keyBy('id');

    $productPresets = [
        1 => [
            [
                'name'     => 'Kvetový med 500g',
                'price'    => '5,50 €',
                'quantity' => 2,
                'image'    => 'images/tovar/vcelie_produkty/med_kvetovy.png',
            ],
            [
                'name'     => 'Včelí vosk 200g',
                'price'    => '2,96 €',
                'quantity' => 1,
                'image'    => 'images/tovar/vcelie_produkty/vosk.png',
            ],
            [
                'name'     => 'Vlašské orechy lúpané 150g',
                'price'    => '4,45 €',
                'quantity' => 1,
                'image'    => 'images/tovar/vcelie_produkty/orechy_vlasske.png',
            ],
        ],
        2 => [
            [
                'name'     => 'Jablká Evelina 1kg',
                'price'    => '3,50 €',
                'quantity' => 5,
                'image'    => 'images/tovar/ovocie/jablka_evelina.png',
            ],
            [
                'name'     => 'Hrušky Konferencia',
                'price'    => '4,00 €',
                'quantity' => 2,
                'image'    => 'images/tovar/ovocie/hrusky.png',
            ],
            [
                'name'     => 'Broskyne 500g',
                'price'    => '3,20 €',
                'quantity' => 1,
                'image'    => 'images/tovar/ovocie/broskyne.png',
            ],
            [
                'name'     => 'Hrozno ružové 300g',
                'price'    => '2,10 €',
                'quantity' => 1,
                'image'    => 'images/tovar/ovocie/hrozno_ruzove.png',
            ],
            [
                'name'     => 'Čučoriedky 125g',
                'price'    => '2,80 €',
                'quantity' => 1,
                'image'    => 'images/tovar/ovocie/cucoriedky.png',
            ],
        ],
        3 => [
            [
                'name'     => 'Tekvica Hokkaido 1kg',
                'price'    => '3,20 €',
                'quantity' => 1,
                'image'    => 'images/tovar/zelenina/tekvica.png',
            ],
            [
                'name'     => 'Mrkva zväzok',
                'price'    => '2,00 €',
                'quantity' => 2,
                'image'    => 'images/tovar/zelenina/mrkva.png',
            ],
        ],
    ];

    $packages = collect($farmIds)->map(function ($farmId) use ($farms, $productPresets) {
        $farm = $farms[$farmId];
        $products = $productPresets[$farmId] ?? [];

        $total = collect($products)->sum(function ($p) {
            $price = floatval(str_replace(',', '.', str_replace(' €', '', $p['price'] ?? '0')));
            return $price * ($p['quantity'] ?? 1);
        });

        return [
            'farm_id'                => $farm->id,
            'farm_name'              => $farm->name,
            'only_personal'          => !$farm->delivery_available,
            'expected_delivery_date' => now()->addDays($farm->avg_delivery_time ?? 3)->format('Y-m-d'),
            'total_price'            => round($total, 2),
            'products'               => $products,
        ];
    })->toArray();

    Session::put('cart.packages', $packages);
    Session::forget('cart.delivery');

    return redirect()->route('cart-form.index');
});
