<?php

use App\Models\User;

it('redirects customer dashboard route to customer page for customers', function () {
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

    $response = $this->actingAs($customer)->get(route('dashboard'));

    $response->assertRedirect(route('customer.dashboard'));
});

it('redirects dashboard route to rider page for riders', function () {
    $rider = User::factory()->create(['role' => User::ROLE_RIDER]);

    $response = $this->actingAs($rider)->get(route('dashboard'));

    $response->assertRedirect(route('rider.dashboard'));
});

it('redirects dashboard route to admin page for admins', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertRedirect(route('admin.dashboard'));
});

it('forbids customer access to rider dashboard', function () {
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

    $response = $this->actingAs($customer)->get(route('rider.dashboard'));

    $response->assertForbidden();
});

it('forbids rider access to customer dashboard', function () {
    $rider = User::factory()->create(['role' => User::ROLE_RIDER]);

    $response = $this->actingAs($rider)->get(route('customer.dashboard'));

    $response->assertForbidden();
});
