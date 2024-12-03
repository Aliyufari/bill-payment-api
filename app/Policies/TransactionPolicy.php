<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    public function index(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }

    public function purchase_airtime(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->user_id;
    }
}
