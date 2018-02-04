<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderHistory(User $user)
    {
        $orders = $user->orderBuy()->latest()->get();
        return response()->json($orders, 200);
    }
}
