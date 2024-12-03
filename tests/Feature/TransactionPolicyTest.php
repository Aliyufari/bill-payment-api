<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();

    $this->transaction = Transaction::factory()->create([
        'user_id' => $this->user->id,
        'transaction_type' => 'airtime',
        'amount' => 500,
    ]);
});

it('allows user to view their transactions', function () {
    $this->actingAs($this->user);
    expect(auth()->user()->can('index', $this->transaction))->toBeTrue();
});

it('denies user from viewing another user transactions', function () {
    $this->actingAs($this->otherUser);

    expect(fn () => Gate::authorize('index', $this->transaction))
        ->toThrow(AuthorizationException::class);
});

it('allows user to purchase airtime for their transaction', function () {
    $this->actingAs($this->user);
    expect(auth()->user()->can('purchase_airtime', $this->transaction))->toBeTrue();
});

it('denies user from purchasing airtime for another user transaction', function () {
    $this->actingAs($this->otherUser);

    expect(fn () => Gate::authorize('purchase_airtime', $this->transaction))
        ->toThrow(AuthorizationException::class);
});
