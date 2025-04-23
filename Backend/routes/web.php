<?php

use Illuminate\Support\Facades\Route;
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