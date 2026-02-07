<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\Auth\CustomerPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop Pages
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ShopController::class, 'show'])->name('shop.product');

// Static Pages
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'sendContact'])->name('contact.send');

// Customer Authentication (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login']);
    Route::get('/register', [CustomerRegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerRegisterController::class, 'register']);
    Route::get('/forgot-password', [CustomerPasswordController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [CustomerPasswordController::class, 'sendReset'])->name('password.email');
    Route::get('/reset-password/{token}', [CustomerPasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [CustomerPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
    Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/{id}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::get('/address', [CheckoutController::class, 'address'])->name('checkout.address');
        Route::post('/address', [CheckoutController::class, 'saveAddress']);
        Route::get('/review', [CheckoutController::class, 'review'])->name('checkout.review');
        Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
        Route::get('/complete/{order}', [CheckoutController::class, 'complete'])->name('checkout.complete');
    });

    // Payment
    Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');

    // Account
    Route::prefix('account')->group(function () {
        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('account.orders');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('account.orders.show');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('account.orders.cancel');

        // Addresses
        Route::get('/addresses', [AddressController::class, 'index'])->name('account.addresses');
        Route::get('/addresses/create', [AddressController::class, 'create'])->name('account.addresses.create');
        Route::post('/addresses', [AddressController::class, 'store'])->name('account.addresses.store');
        Route::delete('/addresses/{id}', [AddressController::class, 'destroy'])->name('account.addresses.destroy');
        Route::put('/addresses/{id}/default', [AddressController::class, 'setDefault'])->name('account.addresses.default');
    });
});

// Razorpay Webhook (no auth)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
