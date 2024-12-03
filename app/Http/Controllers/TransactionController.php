<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseAirtimeRequest;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())->paginate(10);

        if ($transactions->isEmpty()) {
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

        return response()->json([
            'transactions' => $transactions
        ]);
    }

    public function purchaseAirtime(PurchaseAirtimeRequest $request)
    {
        $data = $request->validated();

        $wallet = Auth::user()->wallet;
        
        if (!$wallet) {
            return response()->json([
                'error' => 'Not Found'
            ], 404);
        }

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
