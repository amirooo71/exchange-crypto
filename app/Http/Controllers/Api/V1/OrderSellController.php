<?php

namespace App\Http\Controllers\Api\V1;

use App\OrderSell;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderSellController extends Controller
{
    /**
     * OrderSellController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'currency_id' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $order = OrderSell::create([
            'user_id' => auth()->id(),
            'currency_id' => \request('currency_id'),
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        $order['type'] = 'فروش';

        return response()->json($order, 200);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $orderSell = OrderSell::find($id);
        $orderSell->update([
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        return response()->json($orderSell, 201);
    }

}
