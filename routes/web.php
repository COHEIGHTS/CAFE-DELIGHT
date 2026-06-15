<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ── OTP Verification ────────────────────────────────────────────────────────────
Route::get('/otp/verify', [OtpController::class, 'show'])->name('otp.verify')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify.submit')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

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

    // Cart with rate limiting
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{dish}', [CartController::class, 'add'])->name('cart.add');
        Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
        Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    });

    // Favorites with rate limiting
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites/toggle/{dish}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
        Route::get('/favorites/count', [FavoriteController::class, 'count'])->name('favorites.count');
    });

    // Checkout (FIXED - only once)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/success', fn () => view('order-success'))->name('orders.success');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order.confirmation');
    Route::post('/order/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::post('/order/{order}/mark-paid', [OrderController::class, 'markPaymentAsPaid'])->name('order.markPaid');

    // ── Addresses (ADDED HERE) ────────────────────────────────────────────────
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/',                       [AddressController::class, 'index'])->name('index');
        Route::get('/create',                 [AddressController::class, 'create'])->name('create');
        Route::post('/',                      [AddressController::class, 'store'])->name('store');
        Route::get('/{address}/edit',         [AddressController::class, 'edit'])->name('edit');
        Route::put('/{address}',              [AddressController::class, 'update'])->name('update');
        Route::delete('/{address}',           [AddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/set-default', [AddressController::class, 'setDefault'])->name('default');
    });

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
});

// ── Profile ───────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// ── Admin routes ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/download-pdf', [AnalyticsController::class, 'downloadPDF'])->name('analytics.downloadPDF');

        // Dish management
        Route::get('/menu',             [AdminMenuController::class, 'index'])->name('menu.index');
        Route::get('/menu/create',      [AdminMenuController::class, 'create'])->name('menu.create');
        Route::post('/menu',            [AdminMenuController::class, 'store'])->name('menu.store');
        Route::get('/menu/{id}/edit',   [AdminMenuController::class, 'edit'])->name('menu.edit');
        Route::put('/menu/{id}',        [AdminMenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{id}',     [AdminMenuController::class, 'destroy'])->name('menu.destroy');

        // Order management
        Route::get('/orders',                      [OrderController::class, 'adminIndex'])->name('order.index');
        Route::patch('/order/{order}/status',       [OrderController::class, 'updateStatus'])->name('order.updateStatus');
        Route::post('/order/{order}/approve-payment', [OrderController::class, 'approvePayment'])->name('order.approvePayment');

        // Payments management
        Route::get('/payments',                     [AdminPaymentController::class, 'index'])->name('payments.index');

        // Customers management
        Route::get('/customers',                    [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{user}',             [AdminCustomerController::class, 'show'])->name('customers.show');

        // Settings management
        Route::get('/settings',                     [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings',                     [AdminSettingsController::class, 'update'])->name('settings.update');

        // Audit logs management
        Route::get('/audit-logs',                   [AdminAuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/download-pdf',      [AdminAuditLogController::class, 'downloadPdf'])->name('audit-logs.download-pdf');
    });

require __DIR__.'/auth.php';