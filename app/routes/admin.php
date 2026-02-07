<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group.
|
*/

// Admin Authentication Routes (Guest only)
Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('admin.password.request');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('admin.password.email');
});

// Admin Protected Routes
Route::middleware('admin.auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Profile Management
    Route::get('profile', [AuthController::class, 'profile'])->name('admin.profile');
    Route::put('profile', [AuthController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('profile/password', [AuthController::class, 'updatePassword'])->name('admin.profile.password');

    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

    // Order Management
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update'])->names([
        'index' => 'admin.orders.index',
        'show' => 'admin.orders.show',
        'update' => 'admin.orders.update',
    ]);

    // Customer Management
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'show', 'update'])->names([
        'index' => 'admin.customers.index',
        'show' => 'admin.customers.show',
        'update' => 'admin.customers.update',
    ]);

    // Coupon Management
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->names([
        'index' => 'admin.coupons.index',
        'create' => 'admin.coupons.create',
        'store' => 'admin.coupons.store',
        'show' => 'admin.coupons.show',
        'edit' => 'admin.coupons.edit',
        'update' => 'admin.coupons.update',
        'destroy' => 'admin.coupons.destroy',
    ]);

    // Review Management
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show', 'update', 'destroy'])->names([
        'index' => 'admin.reviews.index',
        'show' => 'admin.reviews.show',
        'update' => 'admin.reviews.update',
        'destroy' => 'admin.reviews.destroy',
    ]);
    Route::post('reviews/bulk-approve', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkApprove'])->name('admin.reviews.bulk-approve');

    // Return Management
    Route::resource('returns', \App\Http\Controllers\Admin\ReturnController::class)->only(['index', 'show', 'update'])->names([
        'index' => 'admin.returns.index',
        'show' => 'admin.returns.show',
        'update' => 'admin.returns.update',
    ]);

    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
});
