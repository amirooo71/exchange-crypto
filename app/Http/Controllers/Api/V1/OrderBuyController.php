<?php

namespace App\Http\Controllers\Api\V1;

use App\OrderBuy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderBuyController extends Controller
{
    /**
     * OrderBuyController constructor.
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

        $order = OrderBuy::create([
            'user_id' => auth()->id(),
            'currency_id' => \request('currency_id'),
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        $order['type'] = 'خرید';

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

        $orderBuy = OrderBuy::find($id);
        $orderBuy->update([
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        return response()->json($orderBuy, 201);
    }

    public function destroy()
    {
    }
}
