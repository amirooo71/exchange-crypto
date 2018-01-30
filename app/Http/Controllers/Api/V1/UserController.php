<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Balance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getBalance(Request $request)
    {
        /**
         * Todo
         *
         * Serialize data then send it as a format json
         */


        $userId = $request->user()->id;
        $balance = Balance::find($userId);
        return response()->json($balance, 200);
    }

    public function getTradesHistory()
    {
    }

    public function getOrders()
    {
    }
}
