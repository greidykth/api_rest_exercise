<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function show (Request $request)
    {
        $accountId = $request->account_id;

        $account = Account::findOrFail($accountId);
        
        return response()->json($account->balance);
    }
}
