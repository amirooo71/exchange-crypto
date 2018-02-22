<?php

namespace App\Http\Controllers\Api\V1;

use App\OrderBuy;
use App\OrderSell;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderBookController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sellOrderBook()
    {

        $orders = OrderSell::where('status', '=', 'in_progress')
            ->orderBy('price', 'desc')
            ->get();

        return response()->json($orders, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function buyOrderBook()
    {
        $orders = OrderBuy::where('status', '=', 'in_progress')
            ->orderBy('price', 'asc')
            ->get();

        return response()->json($orders, 200);
    }
}
