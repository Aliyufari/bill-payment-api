<?php

use App\Models\User;

it('can register user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'John Doe',
        'email' => 'john@mail.com',
        'phone' => '09021212121',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['status', 'message', 'data']);
});

it('can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'aliyu@mail.com',
        'phone' => '09022222222',
        'password' => bcrypt('password'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'aliyu@mail.com',
        'password' => 'password'
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['data' => ['user', 'token']]);
});

it('cannot login with invalid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'fake.user@mail.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Invalid email or password.']);
});
