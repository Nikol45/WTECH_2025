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
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/profile-history', [ProfileHistoryController::class, 'index'])->name('profile-history.index');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/profile-reviews', [ProfileReviewsController::class, 'index'])->name('profile-reviews.index');
Route::get('/cart-delivery', [CartDeliveryController::class, 'index'])->name('cart.delivery');
Route::get('/cart-summary', [CartSummaryController::class, 'index'])->name('cart.summary');


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
Route::get('/test-summary', function () {
    session([
        'cart.items' => [
            // Ovocná farma Slnečný sad
            ['id' => 1, 'farm_product_id' => 1, 'farm_id' => 1, 'label' => '5 ks Jablko Jonagold', 'price' => 3.50],
            ['id' => 2, 'farm_product_id' => 2, 'farm_id' => 1, 'label' => '2 kg Hrušky Konferencia', 'price' => 4.00],
            ['id' => 3, 'farm_product_id' => 3, 'farm_id' => 1, 'label' => '1 ks Džem z čiernych ríbezlí', 'price' => 2.80],

            // Gazdovský dvor Podhorský
            ['id' => 4, 'farm_product_id' => 4, 'farm_id' => 2, 'label' => '1 kg Domáca klobása', 'price' => 6.90],
            ['id' => 5, 'farm_product_id' => 5, 'farm_id' => 2, 'label' => '2 ks Kuracie prsia z voľného chovu', 'price' => 7.40],
            ['id' => 6, 'farm_product_id' => 6, 'farm_id' => 2, 'label' => '1 ks Gazdovský syr', 'price' => 3.20],
            ['id' => 7, 'farm_product_id' => 7, 'farm_id' => 2, 'label' => '0,5 l Ovčí jogurt natural', 'price' => 1.80],

            // Medárstvo U Zlatého úľa
            ['id' => 8, 'farm_product_id' => 8, 'farm_id' => 3, 'label' => '1 ks Kvetový med 500g', 'price' => 5.50],
        ],
        'cart.delivery' => [
            'methods' => [
                1 => 'Osobný odber',
                2 => 'Doručenie na adresu s GLS - express',
                3 => 'Doručenie na adresu s GLS - express',
            ],
            'prices' => [
                1 => 0.00,
                2 => 1.90,
                3 => 1.90,
            ],
        ],
        'cart.paymentMethod' => 'cash_on_pickup',
        'cart.form' => [
            'name' => 'Jana Tichá',
            'email' => 'janaticha@gmail.com',
            'phone' => '+421 111 222 333',
            'billing_address' => [
                'street' => 'Sládkovičova 224',
                'zip' => '841 01',
                'city' => 'Bratislava',
                'country' => 'Slovensko',
            ],
            'delivery_address' => [
                'street' => 'Medová, 24',
                'zip' => '821 02',
                'city' => 'Košice',
                'country' => 'Slovensko',
            ],
            'company' => [
                'ico' => '99988877',
                'name' => 'Zdravo s.r.o.',
                'vat' => 'SK99988877',
            ],
            'note' => 'Prosím zvoniť 2x, vchod je z druhej strany budovy.',
        ]
    ]);

    return redirect()->route('cart-summary.index');
});
Route::get('/test-delivery', function () {
    // Načítanie fariem z DB podľa mena (musia existovať)
    $farm1 = Farm::where('name', 'Repiský-Jánošík')->first();
    $farm2 = Farm::where('name', 'Murčová v.o.s.')->first();

    // Ak nie sú v DB, vráť chybovku
    if (!$farm1 || !$farm2) {
        abort(404, 'Dana farma neexistuje v databáze.');
    }

    Session::put('cart.packages', [
        [
            'farm_id' => $farm1->id,
            'farm_name' => $farm1->name,
            'only_personal' => true,
            'total_price' => 1.99 * 3 + 5.85 * 2 + 6.21 * 2, // Med, vosk, orechy
        ],
        [
            'farm_id' => $farm2->id,
            'farm_name' => $farm2->name,
            'only_personal' => false,
            'total_price' => 0.42 * 10 + 2.15 + 4.21 + 0.78 * 2 + 3.21, // Ovocie
        ]
    ]);

    Session::forget('cart.delivery');

    return redirect()->route('cart-delivery.index');
});


use Illuminate\Support\Carbon;

Route::get('/test-cart-packages', function () {
    // Nastavíme fake balíky s expected_delivery_date (napr. dnes + 3 dni)
    $baseDate = Carbon::now()->addDays(3)->format('Y-m-d');

    Session::put('cart.packages', [
        [
            'farm_id' => 1,
            'farm_name' => 'Včelí raj',
            'only_personal' => true,
            'total_price' => 15.92,
            'expected_delivery_date' => $baseDate,
            'products' => [
                [
                    'name' => 'Kvetový med 500g',
                    'price' => '5,50 €',
                    'quantity' => 2,
                    'image' => 'images/tovar/vcelie_produkty/med_kvetovy.png',
                ],
                [
                    'name' => 'Včelí vosk 200g',
                    'price' => '2,96 €',
                    'quantity' => 1,
                    'image' => 'images/tovar/vcelie_produkty/vosk.png',
                ],
                [
                    'name' => 'Vlašské orechy lúpané 150g',
                    'price' => '4,45 €',
                    'quantity' => 1,
                    'image' => 'images/tovar/vcelie_produkty/orechy_vlasske.png',
                ],
            ],
        ],
        [
            'farm_id' => 2,
            'farm_name' => 'FruitLand',
            'only_personal' => false,
            'total_price' => 24.98,
            'expected_delivery_date' => $baseDate,
            'products' => [
                [
                    'name' => 'Jablká Evelina 1kg',
                    'price' => '3,50 €',
                    'quantity' => 5,
                    'image' => 'images/tovar/ovocie/jablka_evelina.png',
                ],
                [
                    'name' => 'Hrušky Konferencia',
                    'price' => '4,00 €',
                    'quantity' => 2,
                    'image' => 'images/tovar/ovocie/hrusky.png',
                ],
                [
                    'name' => 'Broskyne 500g',
                    'price' => '3,20 €',
                    'quantity' => 1,
                    'image' => 'images/tovar/ovocie/broskyne.png',
                ],
                [
                    'name' => 'Hrozno ružové 300g',
                    'price' => '2,10 €',
                    'quantity' => 1,
                    'image' => 'images/tovar/ovocie/hrozno_ruzove.png',
                ],
                [
                    'name' => 'Čučoriedky 125g',
                    'price' => '2,80 €',
                    'quantity' => 1,
                    'image' => 'images/tovar/ovocie/cucoriedky.png',
                ],
            ],
        ],
    ]);

    // Vymažeme staré záznamy o doprave
    Session::forget('cart.delivery');

    // Presmeruj na výber dopravy
    return redirect()->route('cart-delivery.index');
});
