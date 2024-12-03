<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Requests\AirtimeTransactionRequest;

class TransactionController extends Controller
{
    public function airtime(AirtimeTransactionRequest $request)
    {
        $data = $request->validated();

        $wallet = Auth::user()->wallet;

        if ($wallet->balance < $data['amount']) {
            return response()->json([
                'error' => 'Insufficient balance'
            ], 400);
        }

        $wallet->balance -= $data['amount'];
        $wallet->save();

        Transaction::create([
            'user_id' => Auth::id(),
            'transaction_type' => 'airtime',
            'amount' => $data['amount']
        ]);

        return response()->json([
            'message' => 'Airtime purchased successfully'
        ]);
    }
}
