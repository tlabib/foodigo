<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class, 'customer'])->name('customer.dashboard');
});

Route::middleware(['auth', 'verified', 'role:rider'])->group(function () {
    Route::get('/rider/dashboard', [DashboardController::class, 'rider'])->name('rider.dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/admin/orders', [DashboardController::class, 'admin'])->name('admin.orders.index');
});

require __DIR__.'/auth.php';
