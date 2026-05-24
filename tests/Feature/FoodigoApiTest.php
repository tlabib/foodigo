<?php

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;

it('returns active restaurants from public api', function () {
    Restaurant::factory()->create(['is_active' => true, 'name' => 'Active Spot']);
    Restaurant::factory()->create(['is_active' => false, 'name' => 'Hidden Spot']);

    $response = $this->getJson('/api/restaurants');

    $response->assertSuccessful();
    $response->assertJsonPath('data.0.name', 'Active Spot');
    expect(collect($response->json('data'))->pluck('name'))->not->toContain('Hidden Spot');
});

it('returns available menu items for active restaurant', function () {
    $restaurant = Restaurant::factory()->create(['is_active' => true]);
    $availableItem = MenuItem::factory()->create([
        'restaurant_id' => $restaurant->id,
        'is_available' => true,
    ]);
    MenuItem::factory()->create([
        'restaurant_id' => $restaurant->id,
        'is_available' => false,
    ]);

    $response = $this->getJson("/api/restaurants/{$restaurant->id}/menu-items");

    $response->assertSuccessful();
    $response->assertJsonPath('restaurant.id', $restaurant->id);
    $response->assertJsonCount(1, 'menu_items');
    $response->assertJsonPath('menu_items.0.id', $availableItem->id);
});

it('returns not found for inactive restaurant menu endpoint', function () {
    $restaurant = Restaurant::factory()->create(['is_active' => false]);

    $this->getJson("/api/restaurants/{$restaurant->id}/menu-items")
        ->assertNotFound();
});

it('returns unauthorized when guest tries to place order via api', function () {
    $restaurant = Restaurant::factory()->create(['is_active' => true]);
    $menuItem = MenuItem::factory()->create([
        'restaurant_id' => $restaurant->id,
        'is_available' => true,
    ]);

    $this->postJson('/api/orders', [
        'restaurant_id' => $restaurant->id,
        'menu_item_id' => $menuItem->id,
        'quantity' => 1,
        'delivery_address' => 'House 12, Dhaka',
    ])->assertUnauthorized();
});

it('returns validation error when api order payload is invalid', function () {
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $restaurant = Restaurant::factory()->create(['is_active' => true]);
    $menuItem = MenuItem::factory()->create([
        'restaurant_id' => $restaurant->id,
        'is_available' => true,
    ]);

    $this->actingAs($customer)->postJson('/api/orders', [
        'restaurant_id' => $restaurant->id,
        'menu_item_id' => $menuItem->id,
        'quantity' => 0,
        'delivery_address' => '',
    ])->assertUnprocessable()->assertJsonValidationErrors(['quantity', 'delivery_address']);
});

it('allows customer to place order via api', function () {
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $restaurant = Restaurant::factory()->create(['is_active' => true]);
    $menuItem = MenuItem::factory()->create([
        'restaurant_id' => $restaurant->id,
        'price' => 150,
        'is_available' => true,
    ]);

    $response = $this->actingAs($customer)->postJson('/api/orders', [
        'restaurant_id' => $restaurant->id,
        'menu_item_id' => $menuItem->id,
        'quantity' => 2,
        'delivery_address' => 'Road 10, Dhaka',
    ]);

    $response->assertCreated();
    $response->assertJsonPath('message', 'Order placed successfully.');
    $this->assertDatabaseHas('orders', [
        'user_id' => $customer->id,
        'restaurant_id' => $restaurant->id,
        'status' => Order::STATUS_PENDING,
    ]);
});

it('forbids customer from viewing another customers order via api', function () {
    $owner = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $anotherCustomer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $restaurant = Restaurant::factory()->create(['is_active' => true]);

    $order = Order::factory()->create([
        'user_id' => $owner->id,
        'restaurant_id' => $restaurant->id,
    ]);

    $this->actingAs($anotherCustomer)
        ->getJson("/api/orders/{$order->id}")
        ->assertForbidden();
});

it('returns customer own order details via api', function () {
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $restaurant = Restaurant::factory()->create(['is_active' => true]);

    $order = Order::factory()->create([
        'user_id' => $customer->id,
        'restaurant_id' => $restaurant->id,
    ]);

    $this->actingAs($customer)
        ->getJson("/api/orders/{$order->id}")
        ->assertSuccessful()
        ->assertJsonPath('order.id', $order->id);
});
