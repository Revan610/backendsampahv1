<?php

namespace App\Http\Controllers\Api;

use App\Models\Withdrawals;
use App\Models\Savings;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WithdrawalsController extends Controller
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
            'amount' => 'required|numeric|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            // Cek saldo user
            $user_id = auth()->id(); 
            $saving = Savings::where('user_id', $user_id)->first();
            if (!$saving || $saving->balance < $request->amount) {
                return response()->json(['message' => 'Saldo anda tidak mencukupi'], 400);
            }

            // Kurangi saldo
            $saving->balance -= $request->amount;
            $saving->save();

            // Catat riwayat withdrawal
            $withdrawal = new Withdrawals();
            $withdrawal->user_id = $user_id;
            $withdrawal->amount = $request->amount;
            $withdrawal->withdrawal_date = now();
            $withdrawal->save();

            return response()->json([
                'message' => 'Withdrawal request created successfully',
                'withdrawal' => $withdrawal
            ]);
        });
    }

    public function withdrawals_history(Request $request)
    {
        $userId = auth()->id(); 
        $withdrawals = Withdrawals::where('user_id', $userId)->get();
        return response()->json([
        'data' => $withdrawals
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Withdrawals $withdrawals)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Withdrawals $withdrawals)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Withdrawals $withdrawals)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Withdrawals $withdrawals)
    {
        //
    }
}
