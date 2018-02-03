<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function orderHistory(User $user)
    {
        $orders = $user->orderBuy()->latest()->get();
        return response()->json($orders, 200);
    }

}
