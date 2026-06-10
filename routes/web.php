<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ── Customer dashboard ────────────────────────────────────────────────────────
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ── Customer routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Menu
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/category/{category}', [MenuController::class, 'filterByCategory'])->name('menu.category');
    Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{dish}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/success', fn () => view('order-success'))->name('orders.success');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.confirmation');
    Route::post('/order/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
});

// ── Profile ───────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Dish management
    Route::get('/menu',             [AdminMenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/create',      [AdminMenuController::class, 'create'])->name('menu.create');
    Route::post('/menu',            [AdminMenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{id}/edit',   [AdminMenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}',        [AdminMenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}',     [AdminMenuController::class, 'destroy'])->name('menu.destroy');

    // Order management
    Route::get('/orders',                    [OrderController::class, 'adminIndex'])->name('order.index');
    Route::patch('/order/{order}/status',    [OrderController::class, 'updateStatus'])->name('order.updateStatus');
});

require __DIR__.'/auth.php';