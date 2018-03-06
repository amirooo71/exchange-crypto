<?php

namespace App\Trading\Limit;

use App\Asset;
use App\Balance;
use App\Candle;
use App\Currency;
use App\OrderSell;
use App\OrderBuy;
use App\Pair;
use App\Transaction;
use Illuminate\Support\Facades\DB;

class Exchange
{
    const STATUS_CONFIRMED = "confirmed";
    const STATUS_PARTIAL = "partial";

    /**
     * @var Balance
     */
    protected $balance;

    /**
     * @var OrderSell
     */
    protected $orderSell;

    /**
     * @var OrderBuy
     */
    protected $orderBuy;

    /**
     * Exchange constructor.
     * @param Balance $balance {
     * @param OrderSell $orderSell
     * @param OrderBuy $orderBuy
     */
    public function __construct(Balance $balance, OrderSell $orderSell, OrderBuy $orderBuy)
    {
        $this->balance = $balance;
        $this->orderSell = $orderSell;
        $this->orderBuy = $orderBuy;
    }

    /**
     * @param $order
     * @param $amount
     */
    protected function updateOrderFill($order, $amount)
    {
        $order->updateFill($order->fill + $amount);
        if ($order->fill == $order->amount) {
            $order->updateStatus(Exchange::STATUS_CONFIRMED);
        } else {
            $order->updateStatus(Exchange::STATUS_PARTIAL);
        }

    }

    /**
     * @param $order
     * @return bool
     */
    protected function isFill($order)
    {
        return $order->amount == $order->fill;
    }

    /**
     * @param $order
     * @param $orderBook
     * @param $amount
     * @param $price
     * @param $type
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    protected function saveTransaction($order, $orderBook, $amount, $price, $type)
    {
        return Transaction::create([
            'seller_id' => $orderBook->user_id,
            'buyer_id' => $order->user_id,
            'order_sale_id' => $orderBook->id,
            'order_buy_id' => $order->id,
            'amount' => $amount,
            'price' => $price,
            'type' => $type,
            'timestamp' => ($t = $this->getMicroTime()),
        ]);
    }

    /**
     * @param $aId
     * @param $cId
     * @return mixed
     */
    protected function getPairId($aId, $cId)
    {
        $pairId = Pair::where('asset_id', $aId)
            ->where('currency_id', $cId)->first()->id;

        return $pairId;
    }

    protected function processCandles($aId, $cId, Transaction $transaction)
    {
        $this->processCandle(1, $aId, $cId, $transaction);
        $this->processCandle(5, $aId, $cId, $transaction);
        $this->processCandle(15, $aId, $cId, $transaction);
        $this->processCandle(30, $aId, $cId, $transaction);
        $this->processCandle(60, $aId, $cId, $transaction);
        $this->processCandle(60 * 4, $aId, $cId, $transaction);
        $this->processCandle(60 * 12, $aId, $cId, $transaction);
        $this->processCandle(60 * 24, $aId, $cId, $transaction);
        $this->processCandle(60 * 24 * 3, $aId, $cId, $transaction);
        $this->processCandle(60 * 24 * 7, $aId, $cId, $transaction);
    }

    /**
     * @param int $timeframe Minutes
     * @param $aId
     * @param $cId
     * @param Transaction $transaction
     */
    protected function processCandle($timeframe, $aId, $cId, Transaction $transaction)
    {
        $timestamp = (int)($transaction->timestamp / (60000 * ($timeframe)));

        $candle = Candle::whereT($timestamp)->first();


        if ($candle) {


            DB::update("UPDATE candles SET c=$transaction->price, h=GREATEST(h, $transaction->price), l=LEAST(l, $transaction->price), count=count+1, v=v+$transaction->amount WHERE t=$timestamp");


//            $q = "UPDATE x SET c=:price, h=GREATEST(h, :price), l=LEAST(l, :price), count=count+1, v=v+:amount WHERE t = :timestamp";

        } else {

            Candle::create([
                'pair_id' => $this->getPairId($aId, $cId),
                'time_frame' => $timeframe,
                'o' => $transaction->price,
                'c' => $transaction->price,
                'h' => $transaction->price,
                'l' => $transaction->price,
                'v' => $transaction->amount,
                't' => $timestamp,
                'count' => $transaction->amount,
            ]);

        }
    }

    /**
     * @return float|int
     */
    protected function getResolutionTime()
    {
        return $this->getMicroTime() / 60000;
    }

    /**
     * @return float|int
     */
    private function getMicroTime()
    {
        return microtime(true) * 1000;
    }

}