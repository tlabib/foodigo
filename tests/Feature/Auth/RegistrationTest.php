<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertSuccessful();
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'role' => 'customer',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'customer',
    ]);
});

test('riders can register with rider role', function () {
    $response = $this->post('/register', [
        'name' => 'Rider User',
        'email' => 'rider@example.com',
        'role' => 'rider',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertDatabaseHas('users', [
        'email' => 'rider@example.com',
        'role' => 'rider',
    ]);
});
