<?php

use App\Models\Restaurant;
use App\Models\User;

it('allows admin to create a restaurant', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('admin.restaurants.store'), [
        'name' => 'Food Corner',
        'description' => 'Popular local food.',
        'delivery_time' => 30,
        'rating' => 4.5,
        'is_active' => 1,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('restaurants', [
        'name' => 'Food Corner',
        'is_active' => true,
    ]);
});

it('allows admin to add a menu item to a restaurant', function () {
    $admin = User::factory()->admin()->create();
    $restaurant = Restaurant::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.restaurants.menu-items.store', $restaurant), [
        'name' => 'Chicken Burger',
        'description' => 'Freshly made burger',
        'price' => 250,
        'is_available' => 1,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('menu_items', [
        'restaurant_id' => $restaurant->id,
        'name' => 'Chicken Burger',
    ]);
});

it('forbids non-admin users from restaurant management page', function () {
    $customer = User::factory()->create();

    $response = $this->actingAs($customer)->get(route('admin.restaurants.index'));

    $response->assertForbidden();
});
