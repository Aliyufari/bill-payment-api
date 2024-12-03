<?php

use App\Models\User;
use App\Models\Wallet;

beforeEach(function () {
    $this->user = User::factory()->create();
    Wallet::factory()->create(['user_id' => $this->user->id, 'balance' => 1000]);
    $this->actingAs($this->user, 'api');
});

it('can fetch wallet balance', function () {
    $response = $this->getJson('/api/wallet/balance');

    $response->assertStatus(200)
        ->assertJson(['balance' => 1000]);
});

it('can fund wallet', function () {
    $response = $this->postJson('/api/wallet/fund', [
        'amount' => 1000,
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'Wallet funded successfully']);

    $this->assertDatabaseHas('wallets', ['user_id' => $this->user->id, 'balance' => 2000]);
});
