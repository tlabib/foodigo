<?php

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\User;

it('allows customer to place an order', function () {
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $restaurant = Restaurant::factory()->create(['is_active' => true]);
    $menuItem = MenuItem::factory()->create([
        'restaurant_id' => $restaurant->id,
        'price' => 100,
        'is_available' => true,
    ]);

    $response = $this->actingAs($customer)->post(route('customer.orders.store'), [
        'restaurant_id' => $restaurant->id,
        'menu_item_id' => $menuItem->id,
        'quantity' => 2,
        'delivery_address' => 'House 12, Dhaka',
    ]);

    $response->assertRedirect(route('customer.orders.index'));
    $this->assertDatabaseHas('orders', [
        'user_id' => $customer->id,
        'restaurant_id' => $restaurant->id,
        'status' => 'pending',
    ]);
});
