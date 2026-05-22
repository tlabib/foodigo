<?php

use App\Models\User;

it('allows admin to view users management page', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertSuccessful();
});

it('filters users by role', function () {
    $admin = User::factory()->admin()->create();
    $customer = User::factory()->create([
        'role' => User::ROLE_CUSTOMER,
        'email' => 'customer-filter@example.com',
    ]);
    $rider = User::factory()->create([
        'role' => User::ROLE_RIDER,
        'email' => 'rider-filter@example.com',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'rider']));

    $response->assertSuccessful();
    $response->assertSee($rider->email);
    $response->assertDontSee($customer->email);
});

it('forbids non-admin from viewing users management page', function () {
    $customer = User::factory()->create(['role' => User::ROLE_CUSTOMER]);

    $response = $this->actingAs($customer)->get(route('admin.users.index'));

    $response->assertForbidden();
});
