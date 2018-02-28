<?php

namespace App\Http\Controllers\Api\V1;

use App\Asset;
use App\Balance;
use App\Currency;
use App\Events\OrderBook;
use App\Http\Requests\StoreOrderSell;
use App\OrderSell;
use App\Trading\Limit\Sell;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
        $assetSymbol = Asset::find($request->asset_id)->symbol;
        $currencyId = Currency::whereSymbol($assetSymbol)->first()->id;
        if ($this->isValidOrder($currencyId)) {
            $this->decrementUserBalance($currencyId, $request->amount);
            $order = OrderSell::storeOrder();
            $validOrder = OrderSell::find($order->id);
            $exchanger->process($validOrder);
            OrderBook::dispatch($validOrder);
            return response()->json([], 200);
        }
        return \response()->json([], 403);
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
        $order = OrderSell::find($id);
        $this->incrementUserBalance(2, $order->remainAmount());
        $order->delete();
        return response()->json([], 204);
    }

    /**
     * @return float|int
     */
    private function totalPrice()
    {
        return (\request()->amount * \request()->price);
    }

    /**
     * @param $currency
     * @return bool
     */
    private function isValidOrder($currency): bool
    {
        $balance = $this->balance->getUserBalance($currency);
        return \request()->amount < $balance->available && $this->totalPrice() != 0;
    }

    /**
     * @param $currencyId
     * @param $amount
     * @return mixed
     */
    private function decrementUserBalance($currencyId, $amount)
    {
        return DB::table('balances')
            ->where('user_id', auth()->user()->id)
            ->where('currency_id', $currencyId)
            ->decrement('available', $amount);
    }

    /**
     * @param $currencyId
     * @param $amount
     */
    private function incrementUserBalance($currencyId, $amount): void
    {
        DB::table('balances')
            ->where('user_id', auth()->user()->id)
            ->where('currency_id', $currencyId)
            ->increment('available', $amount);
    }

}
