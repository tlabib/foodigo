<?php

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;

it('allows admin to update order status and assign rider', function () {
    $admin = User::factory()->admin()->create();
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $rider = User::factory()->create(['role' => User::ROLE_RIDER]);
    $restaurant = Restaurant::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'restaurant_id' => $restaurant->id,
        'status' => Order::STATUS_PENDING,
    ]);

    $response = $this->actingAs($admin)->patch(route('admin.orders.update', $order), [
        'status' => Order::STATUS_CONFIRMED,
        'rider_id' => $rider->id,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_CONFIRMED,
        'rider_id' => $rider->id,
    ]);
    $this->assertDatabaseHas('order_status_histories', [
        'order_id' => $order->id,
        'status' => Order::STATUS_CONFIRMED,
    ]);
});
