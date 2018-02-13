<?php

namespace App\Http\Controllers\Api\V1;

use App\Balance;
use App\OrderBuy;
use App\Services\BuyExchanger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
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
     * @param BuyExchanger $exchanger
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, BuyExchanger $exchanger)
    {
        $this->validate($request, [
            'currency_id' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $userBalance = \App\Balance::where('user_id', '=', auth()->user()->id)->where('currency_id', '=', 2)->first();
        $requestedAmount = ($request->amount * $request->price);
        if ($requestedAmount > $userBalance->amount) {
            return \response()->json([], Response::HTTP_FORBIDDEN);
        }

        $remainAmount = ($userBalance->amount - $requestedAmount);
        $userBalance->update(['amount' => $remainAmount]);

        $order = OrderBuy::create([
            'user_id' => auth()->id(),
            'currency_id' => \request('currency_id'),
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        $order['type'] = 'خرید';

        $validOrder = OrderBuy::find($order->id);

        $exchanger->process($validOrder);

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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        OrderBuy::find($id)->delete();
        return response()->json([], 204);
    }
}
