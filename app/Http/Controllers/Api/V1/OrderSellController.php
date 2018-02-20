<?php

namespace App\Http\Controllers\Api\V1;

use App\Balance;
use App\Http\Requests\StoreOrderSell;
use App\OrderSell;
use App\Trading\Limit\Sell;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class OrderSellController extends Controller
{
    /**
     * @var Balance
     */
    protected $balance;

    /**
     * OrderSellController constructor.
     * @param Balance $balance
     */
    public function __construct(Balance $balance)
    {
        $this->middleware('auth:api');
        $this->balance = $balance;
    }

    /**
     * @param Request $request
     * @param StoreOrderSell $validation
     * @param Sell $exchanger
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, StoreOrderSell $validation, Sell $exchanger)
    {
        $userBalance = $this->balance->getUserBalance(2);
        if ($this->isNotValidAmount($userBalance)) {
            return \response()->json([], Response::HTTP_FORBIDDEN);
        }
        $userBalance->update(['available' => $this->calculateAvailableAmount($userBalance)]);
        $order = OrderSell::storeOrder();
        $validOrder = OrderSell::find($order->id);
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

        $orderSell = OrderSell::find($id);
        $orderSell->update([
            'price' => \request('price'),
            'amount' => \request('amount'),
        ]);

        return response()->json($orderSell, 201);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        OrderSell::find($id)->delete();
        return response()->json([], 204);
    }

    /**
     * @param $userBalance
     * @return bool
     */
    private function isNotValidAmount($userBalance): bool
    {
        return \request()->amount > $userBalance->available;
    }

    /**
     * @param $userBalance
     * @return mixed
     */
    private function calculateAvailableAmount($userBalance)
    {
        return $userBalance->available - \request()->amount;
    }

}
