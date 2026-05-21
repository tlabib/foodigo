<?php

use App\Models\User;

it('allows admins to access admin orders', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.orders.index'));

    $response->assertSuccessful();
});

it('forbids non-admins from admin orders', function () {
    $customer = User::factory()->create();

    $response = $this->actingAs($customer)->get(route('admin.orders.index'));

    $response->assertForbidden();
});

it('redirects guests from admin orders', function () {
    $response = $this->get(route('admin.orders.index'));

    $response->assertRedirect(route('login'));
});
