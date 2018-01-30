<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExchangeController extends Controller
{
    public function order(Request $request)
    {
        $order = new Order();
        $order->user_id = $request->user()->id;
        $order->currency_id = $request->currency;
        $order->amount = $request->amount;
        $order->price = $request->price;
        $order->type = $request->type;
        $order->save();

    }
}
