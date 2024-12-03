<?php

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();

    $this->wallet = Wallet::factory()->create([
        'user_id' => $this->user->id,
        'balance' => 1000,
    ]);
});

it('allows users to view their wallet balance', function () {
    $this->actingAs($this->user);
    expect(Auth::user()->can('balance', $this->wallet))->toBeTrue();
});

it('denies user from viewing another user wallet balance', function () {
    $this->actingAs($this->otherUser);

    expect(fn () => Gate::authorize('balance', $this->wallet))
        ->toThrow(AuthorizationException::class);
});

it('allows user to fund their wallet', function () {
    $this->actingAs($this->user);
    expect(Auth::user()->can('fund', $this->wallet))->toBeTrue();
});

it('denies user from funding another user wallet', function () {
    $this->actingAs($this->otherUser);

    expect(fn () => Gate::authorize('fund', $this->wallet))
        ->toThrow(AuthorizationException::class);
});
