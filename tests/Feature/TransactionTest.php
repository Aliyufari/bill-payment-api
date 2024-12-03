<?php

use App\Models\User;
use App\Models\Wallet;

// beforeEach(function () {
//     $this->user = User::factory()->create();
//     Wallet::factory()->create(['user_id' => $this->user->id, 'balance' => 1000]);
//     $this->actingAs($this->user, 'api');
// });

// it('can buy airtime if balance is sufficient', function () {
//     $response = $this->postJson('/api/transactions/airtime', [
//         'amount' => 200,
//     ]);

//     $response->assertStatus(200)
//         ->assertJson(['message' => 'Airtime purchased successfully']);

//     $this->assertDatabaseHas('wallets', ['user_id' => $this->user->id, 'balance' => 800]);
// });

// it('cannot buy airtime if balance is insufficient', function () {
//     $response = $this->postJson('/api/transactions/airtime', [
//         'amount' => 1200,
//     ]);

//     $response->assertStatus(400)
//         ->assertJson(['error' => 'Insufficient balance']);
// });
