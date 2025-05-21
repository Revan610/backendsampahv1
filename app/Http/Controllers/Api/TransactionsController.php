<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Transaction_details;
use App\Models\Wastes;
use App\Models\Savings;
use App\Models\Withdrawals;
use App\Models\User;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'waste_id' => 'required|exists:wastes,id',
        'weight' => 'required|numeric|min:0.1'
    ]);

    return DB::transaction(function () use ($request) {
        $user_id = auth()->id();
        $waste = Wastes::findOrFail($request->waste_id);
        $price = $request->weight * $waste->price_per_kg;

        $transaction = new Transactions();
        $transaction->user_id = $user_id;
        $transaction->total_weight = $request->weight;
        $transaction->total_price = $price;
        $transaction->transaction_date = now();
        $transaction->save();


        $transactiond = new Transaction_details();
        $transactiond->transaction_id = $transaction->id;
        $transactiond->waste_id = $waste->id;
        $transactiond->weight = $request->weight;
        $transactiond->price = $price;
        $transactiond->save();

        $saving = Savings::firstOrCreate(
            ['user_id' => $user_id],
            ['balance' => 0]
        );
        $saving->balance += $price;
        $saving->save();
        return response()->json([
            'message' => 'Transaction created successfully',
            'transaction' => $transaction
        ], 201);
    });

}


    public function transaction_history(Request $request)
    {
        $userId = auth()->id();
        $transaction = Transactions::where('user_id', $userId)->get();
        return response()->json([
        'data' => $transaction
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Transactions $transactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transactions $transactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transactions $transactions)
    {
        //
    }
}
