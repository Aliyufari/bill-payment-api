<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseAirtimeRequest;

class TransactionController extends Controller
{
    public function index()
    {
        $this->authorize('index');
        return response()->json([
            'transactions' => Transaction::paginate(10)
        ]);
    }

    public function purchase_airtime(PurchaseAirtimeRequest $request)
    {
        $this->authorize('purchase_airtime');

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
