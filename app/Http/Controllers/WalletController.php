<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FundWalletRequest;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;

class WalletController extends Controller
{
    public function balance()
    {
        $wallet = Auth::user()->wallet;
        return response()->json([
            'balance' => $wallet->balance
        ]);
    }

    public function fund(FundWalletRequest $request)
    {
        $data = $request->validated();

        $wallet = Auth::user()->wallet;
        $wallet->balance += $data['amount'];
        $wallet->save();

        Transaction::create([
            'user_id' => Auth::id(),
            'transaction_type' => 'fund',
            'amount' => $data['amount']
        ]);

        return response()->json([
            'message' => 'Wallet funded successfully'
        ]);
    }
}