<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DishController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ── Customer dashboard ────────────────────────────────────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Customer Menu (view only) ─────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/{id}', [MenuController::class, 'show'])->name('menu.show');
    Route::get('/menu/category/{category}', [MenuController::class, 'filterByCategory'])->name('menu.category');

    // ── Cart Routes ───────────────────────────────────────────────────────────
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{dish}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

    // ── Checkout Routes ──────────────────────────────────────────────────────
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('order.store');

    // ── Order Routes ─────────────────────────────────────────────────────────
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.confirmation');
    Route::post('/order/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
});

// ── Profile (auth only) ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Admin Routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Dish Management
    Route::get('/menu', [DishController::class, 'index'])->name('menu.index');
    Route::get('/menu/create', [DishController::class, 'create'])->name('menu.create');
    Route::post('/menu', [DishController::class, 'store'])->name('menu.store');
    Route::get('/menu/{id}/edit', [DishController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}', [DishController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [DishController::class, 'destroy'])->name('menu.destroy');

    // ── Order Management ─────────────────────────────────────────────────────
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('order.index');
    Route::patch('/order/{order}/status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
});

require __DIR__.'/auth.php';