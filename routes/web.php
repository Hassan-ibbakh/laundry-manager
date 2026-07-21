<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\LaundryController as AdminLaundry;
use App\Http\Controllers\Laundry\AuthController as LaundryAuth;
use App\Http\Controllers\Laundry\DashboardController as LaundryDashboard;
use App\Http\Controllers\Laundry\ClientController;
use App\Http\Controllers\Laundry\OrderController;
use App\Http\Controllers\TrackingController;

// ─── Admin Auth ───────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuth::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuth::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuth::class, 'logout'])->name('logout');

    // Admin Protected Routes
    Route::middleware('auth.admin')->group(function () {
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::resource('laundries', AdminLaundry::class);
    });
});

// ─── Laundry Auth ─────────────────────────────────────────
Route::prefix('laundry')->name('laundry.')->group(function () {
    // Routes de connexion (sans middleware)
    Route::get('login', [LaundryAuth::class, 'showLogin'])->name('login');
    Route::post('login', [LaundryAuth::class, 'login'])->name('login.post');
    Route::post('logout', [LaundryAuth::class, 'logout'])->name('logout');

    // Routes protégées par authentification
    Route::middleware('auth.laundry')->group(function () {
        // Dashboard
        Route::get('dashboard', [LaundryDashboard::class, 'index'])->name('dashboard');

        // Clients
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
        Route::get('clients/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('clients/{id}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('orders/{id}/pdf', [OrderController::class, 'pdf'])->name('orders.pdf');
        Route::get('orders/{id}/whatsapp', [OrderController::class, 'whatsapp'])->name('orders.whatsapp');
    });
});

// ─── Public Tracking ──────────────────────────────────────
Route::get('suivi/{tracking_token}', [TrackingController::class, 'show'])->name('tracking.show');

// ─── Redirection Root ─────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('laundry.login');
});