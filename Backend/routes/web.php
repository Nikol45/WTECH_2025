<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\FarmProductController;
use App\Http\Controllers\ProfileFavouriteController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartFormController;
use App\Http\Controllers\CartDeliveryController;
use App\Http\Controllers\CartSummaryController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileHistoryController;
use App\Http\Controllers\ProfileReviewsController;

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

Route::get('/favourites', [ProfileFavouriteController::class, 'index'])->name('favourites.index');

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/history', [ProfileHistoryController::class, 'index'])->name('history');
    Route::get('/favourites', [ProfileFavouriteController::class, 'index'])->name('favourites');
    Route::get('/reviews', [ProfileReviewsController::class, 'index'])->name('reviews');
});

Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('view/admin', [ProfileController::class, 'viewAsAdmin'])->name('profile.admin');
    Route::post('view/customer', [ProfileController::class, 'viewAsCustomer'])->name('profile.customer');
    Route::post('admin/create', [ProfileController::class, 'createAdmin'])->name('profile.admin.create');
});

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/history', [ProfileHistoryController::class, 'index'])->name('history');
    Route::post('/history/{order}/reorder', [ProfileHistoryController::class, 'reorder'])->name('history.reorder');

    Route::get('/favourites', [ProfileFavouriteController::class, 'index'])->name('favourites');
    Route::post('/favourites', [ProfileFavouriteController::class, 'store'])->name('favourites.store');
    Route::delete('/favourites/{review}', [ProfileFavouriteController::class, 'store'])->name('favourites.destroy');

    Route::get('/reviews', [ProfileReviewsController::class, 'index'])->name('reviews');
    Route::post('/reviews', [ProfileReviewsController::class, 'store'])->name('reviews.store'); // nový záznam
    Route::patch('/reviews/{review}', [ProfileReviewsController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ProfileReviewsController::class, 'destroy'])->name('reviews.destroy');
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

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/farm/store',    [ProfileController::class, 'storeFarm'   ])->name('farm.store');
    Route::post('/article/store', [ProfileController::class, 'storeArticle'])->name('article.store');
    Route::post('/review/store',  [ProfileController::class, 'storeReview' ])->name('review.store');
});

Route::post('/admin/review/{review}/reply', [ProfileController::class, 'replyToReview'])->name('admin.review.reply');

Route::get('farms/{farm}', [FarmController::class, 'show'])->name('farms.show');
Route::delete('/products/{product}', [FarmProductController::class, 'destroy'])->name('products.destroy');
Route::put('/products/{product}', [FarmProductController::class, 'update'])->name('products.update');

Route::resource('farms.products', FarmProductController::class)->shallow();
Route::resource('articles', ArticleController::class);
Route::resource('cart-items', CartItemController::class);
Route::resource('farms', FarmController::class);
Route::resource('farm-products', FarmProductController::class);
Route::resource('order-items', OrderItemController::class);
Route::resource('cart-form',  CartFormController::class);
Route::resource('cart-delivery',  CartDeliveryController::class);
Route::resource('cart-summary',  CartSummaryController::class);

Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login',    [LoginController::class,    'login'])->name('login');
Route::post('/logout',   [LoginController::class,    'logout'])->name('logout')->middleware('web');
Route::get ('/guest',    [GuestController::class,    'browseAsGuest'])->name('guest.browse');

Route::view('/obchodne-podmienky', 'static.terms')->name('terms');

Route::resource('reviews', ProfileReviewsController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware('auth');

//testers
Route::get('/test-login', function () {
    $user = \App\Models\User::where('is_admin', true)->skip(2)->firstOrFail(); // vezme prveho admina, ->skip(1) pre druheho
    Auth::login($user);
    return redirect()->route('profile.index');
});

