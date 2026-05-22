<?php

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('allows assigned rider to update delivery status', function () {
    $rider = User::factory()->create(['role' => User::ROLE_RIDER]);
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $restaurant = Restaurant::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'restaurant_id' => $restaurant->id,
        'rider_id' => $rider->id,
        'status' => Order::STATUS_OUT_FOR_DELIVERY,
    ]);

    $response = $this->actingAs($rider)->patch(route('rider.orders.update-status', $order), [
        'status' => Order::STATUS_DELIVERED,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => Order::STATUS_DELIVERED,
    ]);
});

it('forbids non assigned rider to update order', function () {
    $riderA = User::factory()->create(['role' => User::ROLE_RIDER]);
    $riderB = User::factory()->create(['role' => User::ROLE_RIDER]);
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $restaurant = Restaurant::factory()->create();
    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'restaurant_id' => $restaurant->id,
        'rider_id' => $riderA->id,
    ]);

    $this->withoutExceptionHandling();
    $this->expectException(HttpException::class);

    $this->actingAs($riderB)->patch(route('rider.orders.update-status', $order), [
        'status' => Order::STATUS_DELIVERED,
    ]);
});
