<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\RestaurantCatalogController;
use App\Http\Controllers\RiderOrderController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\RestaurantManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ProfileController;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $restaurants = Restaurant::query()
        ->active()
        ->orderByDesc('id')
        ->paginate(8);

    return view('welcome', compact('restaurants'));
})->name('home');
Route::get('/restaurants/{restaurant}', [RestaurantCatalogController::class, 'show'])->name('restaurants.show');



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// customer routes
Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardController::class, 'customer'])->name('customer.dashboard');
    Route::post('/restaurants/{restaurant}/menu-items/{menuItem}/cart', [RestaurantCatalogController::class, 'addToCart'])
        ->name('customer.cart.add');
    Route::get('/customer/cart', [CustomerOrderController::class, 'cart'])->name('customer.cart');
    Route::patch('/customer/cart/items/{menuItem}', [CustomerOrderController::class, 'updateCartItem'])->name('customer.cart.items.update');
    Route::delete('/customer/cart/items/{menuItem}', [CustomerOrderController::class, 'removeCartItem'])->name('customer.cart.items.destroy');
    Route::get('/customer/orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/customer/orders/create', [CustomerOrderController::class, 'create'])->name('customer.orders.create');
    Route::post('/customer/orders', [CustomerOrderController::class, 'store'])->name('customer.orders.store');
});

// rider routes

Route::middleware(['auth', 'verified', 'role:rider'])->group(function () {
    Route::get('/rider/dashboard', [DashboardController::class, 'rider'])->name('rider.dashboard');
    Route::get('/rider/orders', [RiderOrderController::class, 'index'])->name('rider.orders.index');
    Route::patch('/rider/orders/{order}/status', [RiderOrderController::class, 'updateStatus'])->name('rider.orders.update-status');
});

//admin routes

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/admin/orders', [OrderManagementController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [OrderManagementController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}', [OrderManagementController::class, 'update'])->name('admin.orders.update');
    Route::get('/admin/restaurants', [RestaurantManagementController::class, 'index'])
        ->name('admin.restaurants.index');
    Route::get('/admin/restaurants/{restaurant}', [RestaurantManagementController::class, 'show'])
        ->name('admin.restaurants.show');
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/restaurants', [RestaurantManagementController::class, 'store'])
        ->name('admin.restaurants.store');
    Route::patch('/admin/restaurants/{restaurant}', [RestaurantManagementController::class, 'update'])
        ->name('admin.restaurants.update');
    Route::delete('/admin/restaurants/{restaurant}', [RestaurantManagementController::class, 'destroy'])
        ->name('admin.restaurants.destroy');
    Route::patch('/admin/restaurants/{restaurant}/toggle-active', [RestaurantManagementController::class, 'toggleActive'])
        ->name('admin.restaurants.toggle-active');
    Route::post('/admin/restaurants/{restaurant}/menu-items', [RestaurantManagementController::class, 'storeMenuItem'])
        ->name('admin.restaurants.menu-items.store');
    Route::patch('/admin/restaurants/{restaurant}/menu-items/{menuItem}', [RestaurantManagementController::class, 'updateMenuItem'])
        ->name('admin.restaurants.menu-items.update');
    Route::delete('/admin/restaurants/{restaurant}/menu-items/{menuItem}', [RestaurantManagementController::class, 'destroyMenuItem'])
        ->name('admin.restaurants.menu-items.destroy');
    Route::patch('/admin/restaurants/{restaurant}/menu-items/{menuItem}/toggle', [RestaurantManagementController::class, 'toggleMenuItemAvailability'])
        ->name('admin.restaurants.menu-items.toggle-availability');
});

require __DIR__.'/auth.php';
