<?php

use App\Http\Controllers\Api\FoodigoApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:public-api')->group(function () {
    Route::get('/restaurants', [FoodigoApiController::class, 'restaurants']);
    Route::get('/restaurants/{restaurant}/menu-items', [FoodigoApiController::class, 'restaurantMenuItems']);
});

Route::middleware(['web', 'auth', 'role:customer'])->group(function () {
    Route::post('/orders', [FoodigoApiController::class, 'storeOrder'])->middleware('throttle:orders-create-api');
    Route::get('/orders/{order}', [FoodigoApiController::class, 'showOwnOrder'])->middleware('throttle:authenticated-api');
});
