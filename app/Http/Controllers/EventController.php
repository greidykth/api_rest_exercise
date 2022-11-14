<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function store (Request $request)
    {
        if ($request->type === 'deposit') {
            return $this->deposit(
                $request->destination,
                $request->amount
            );
        }elseif ($request->type === 'withdraw') {
            return $this->withdraw(
                $request->origin,
                $request->amount
            );
        }
    }

    private function deposit($destination, $amount)
    {
        $account = Account::firstOrCreate([
            'id' => $destination
        ]);

        $account->balance += $amount;
        $account->save();
        
        return response()->json([
            'destination' => [
                'id' => $account->id,
                'balance' => $account->balance
            ]
        ], 201);
    }
    private function withdraw($origin, $amount)
    {
        $account = Account::findOrFail($origin);

        $account->balance -= $amount;
        $account->save();
        
        return response()->json([
            'origin' => [
                'id' => $account->id,
                'balance' => $account->balance
            ]
        ], 201);
    }
}
