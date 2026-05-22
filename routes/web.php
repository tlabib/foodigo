<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RestaurantManagementController;
use App\Http\Controllers\Admin\UserManagementController;
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
    Route::get('/admin/restaurants', [RestaurantManagementController::class, 'index'])->name('admin.restaurants.index');
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/restaurants', [RestaurantManagementController::class, 'store'])->name('admin.restaurants.store');
    Route::patch('/admin/restaurants/{restaurant}/toggle-active', [RestaurantManagementController::class, 'toggleActive'])
        ->name('admin.restaurants.toggle-active');
    Route::post('/admin/restaurants/{restaurant}/menu-items', [RestaurantManagementController::class, 'storeMenuItem'])
        ->name('admin.restaurants.menu-items.store');
});

require __DIR__.'/auth.php';
