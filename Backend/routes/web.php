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

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GuestController;

use App\Models\Farm;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'homepage'])->name('homepage');
Route::get('/cart-items', [CartItemController::class, 'index'])->name('cart-items.index');
Route::post('cart-items', [CartItemController::class,'store'])->name('cart.store');
Route::delete('/cart-items',[CartItemController::class,'destroy'])->name('cart.destroy');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{farm_product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart/confirmation', [CartSummaryController::class, 'confirmation'])->name('cart.confirmation');
Route::patch('cart-items/{farm_product}', [CartItemController::class,'update'])->name('cart.updateQuantity');

Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/history', [ProfileHistoryController::class, 'index'])->name('history');
    Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites');
    Route::get('/reviews', [ProfileReviewsController::class, 'index'])->name('reviews');
});

Route::prefix('profile/update')->name('profile.update.')->middleware('auth')->group(function () {
    Route::post('/name', [ProfileController::class, 'updateName'])->name('name');
    Route::post('/email', [ProfileController::class, 'updateEmail'])->name('email');
    Route::post('/phone', [ProfileController::class, 'updatePhone'])->name('phone');
    Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password');
    Route::post('/billing', [ProfileController::class, 'updateBillingAddress'])->name('billing');
    Route::post('/delivery', [ProfileController::class, 'updateDeliveryAddress'])->name('delivery');
    Route::post('/company', [ProfileController::class, 'updateCompany'])->name('company');
    Route::post('/nickname', [ProfileController::class, 'updateNickname'])->name('nickname');
    Route::post('/icon', [ProfileController::class, 'updateIcon'])->name('icon');
});

Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('view/admin', [ProfileController::class, 'viewAsAdmin'])->name('profile.admin');
    Route::post('view/customer', [ProfileController::class, 'viewAsCustomer'])->name('profile.customer');
    Route::post('admin/create', [ProfileController::class, 'createAdmin'])->name('profile.admin.create');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/farm/store',    [ProfileController::class, 'storeFarm'   ])->name('farm.store');
    Route::post('/article/store', [ProfileController::class, 'storeArticle'])->name('article.store');
    Route::post('/review/store',  [ProfileController::class, 'storeReview' ])->name('review.store');

});

Route::post('/admin/review/{review}/reply', [ProfileController::class, 'replyToReview'])->name('admin.review.reply');

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

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login',    [LoginController::class,    'login'])->name('login');
Route::post('/logout',   [LoginController::class,    'logout'])->name('logout')->middleware('web');
Route::get ('/guest',    [GuestController::class,    'browseAsGuest'])->name('guest.browse');

Route::view('/obchodne-podmienky', 'static.terms')->name('terms');

//testers
Route::get('/test-cart', function () {
    $farmIds = [1, 2];
    $farms = Farm::whereIn('id', $farmIds)->get()->keyBy('id');

    $productPresets = [
        1 => [
            [
                'farm_product_id' => 1,
                'name'     => 'Kvetový med 500g',
                'price'    => '5,50 €',
                'quantity' => 2,
            ],
            [
                'farm_product_id' => 2,
                'name'     => 'Včelí vosk 200g',
                'price'    => '2,96 €',
                'quantity' => 1,
            ],
            [
                'farm_product_id' => 3,
                'name'     => 'Vlašské orechy lúpané 150g',
                'price'    => '4,45 €',
                'quantity' => 1,
            ],
        ],
        2 => [
            [
                'farm_product_id' => 4,
                'name'     => 'Jablká Evelina 1kg',
                'price'    => '3,50 €',
                'quantity' => 5,
            ],
            [
                'farm_product_id' => 5,
                'name'     => 'Hrušky Konferencia',
                'price'    => '4,00 €',
                'quantity' => 2,
            ],
            [
                'farm_product_id' => 6,
                'name'     => 'Broskyne 500g',
                'price'    => '3,20 €',
                'quantity' => 1,
            ],
            [
                'farm_product_id' => 7,
                'name'     => 'Hrozno ružové 300g',
                'price'    => '2,10 €',
                'quantity' => 1,
            ],
            [
                'farm_product_id' => 8,
                'name'     => 'Čučoriedky 125g',
                'price'    => '2,80 €',
                'quantity' => 1,
            ],
        ],
    ];

    $items = [];

    foreach ($productPresets as $farmId => $products) {
        $farm = $farms[$farmId] ?? null;
        if (!$farm) continue;

        foreach ($products as $product) {
            $price = floatval(str_replace(',', '.', str_replace(' €', '', $product['price'])));
            $items[] = [
                'farm_product_id' => $product['farm_product_id'],
                'farm_id'         => $farm->id,
                'label'           => $product['name'],
                'quantity'        => $product['quantity'],
                'price'           => $price,
            ];
        }
    }

    Session::put('cart.items', $items);

    return redirect()->route('cart-form.index');
});

Route::get('/test-login', function () {
    $user = \App\Models\User::findOrFail(3);
    Auth::login($user);
    return redirect()->route('profile.index');
});
