<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TransactionPolicy
{
    public function index(User $user): bool
    {
        return true;
    }

    public function purchaseAirtime(User $user, Transaction $transaction): bool
    {
        return true;
    }
}
