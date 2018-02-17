<?php

namespace App\Http\Controllers\Api\V1;

use App\Balance;
use App\Http\Requests\StoreOrderBuy;
use App\OrderBuy;
use App\Services\BuyExchanger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderBuyController extends Controller
{

    /**
     * @var Balance
     */
    protected $balance;

    /**
     * OrderBuyController constructor.
     * @param Balance $balance
     */
    public function __construct(Balance $balance)
    {
        $this->middleware('auth:api');
        $this->balance = $balance;
    }

    /**
     * @param Request $request
     * @param BuyExchanger $exchanger
     * @param StoreOrderBuy $validation
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, BuyExchanger $exchanger, StoreOrderBuy $validation)
    {
        $userBalance = $this->balance->getUserBalance(1);
        if ($this->isNotValidAmount($userBalance, $this->requestedAmount($request))) {
            return \response()->json([], Response::HTTP_FORBIDDEN);
        }
        $userBalance->update(['available' => $this->calculateAvailableAmount($userBalance, $request)]);
        $order = OrderBuy::storeOrder();
        $validOrder = OrderBuy::find($order->id);
        $exchanger->process($validOrder);
        return response()->json($order, Response::HTTP_OK);
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

    /**
     * @param $userBalance
     * @param $requestedAmount
     * @return bool
     */
    private function isNotValidAmount($userBalance, $requestedAmount)
    {
        return ($requestedAmount > $userBalance->available);
    }

    /**
     * @param $request
     * @return float|int
     */
    private function requestedAmount($request)
    {
        return ($request->amount * $request->price);
    }

    /**
     * @param $userBalance
     * @param $request
     * @return float|int
     */
    private function calculateAvailableAmount($userBalance, $request)
    {
        return $userBalance->available - $this->requestedAmount($request);
    }
}
