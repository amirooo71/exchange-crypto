<?php

namespace App\Trading\Limit;

use App\Events\OrderConfirm;
use Illuminate\Support\Facades\DB;

class Sell extends Exchange
{

    /**
     * @param $order
     */
    public function process($order)
    {

        foreach ($this->orderBuy->orderBook($order) as $orderBook) {

            if ($this->isFill($order)) {

                break;

            }

            $price = $orderBook->price;
            $amount = min($order->remainAmount(), $orderBook->remainAmount());
            $totalPrice = $amount * $price;
            $cId = $order->pair->currency_id; //1
            $aId = $order->pair->asset_id; //2

            //Seller Balance Calculation
            DB::table('balances')->where('user_id', $order->user_id)->where('ac_id', $cId)->increment('amount', $totalPrice);
            DB::table('balances')->where('user_id', $order->user_id)->where('ac_id', $cId)->increment('available', $totalPrice);
            DB::table('balances')->where('user_id', $order->user_id)->where('ac_id', $aId)->decrement('amount', $amount);

            //Buyer Balance Calculation
            DB::table('balances')->where('user_id', $orderBook->user_id)->where('ac_id', $cId)->decrement('amount', $totalPrice);
            DB::table('balances')->where('user_id', $orderBook->user_id)->where('ac_id', $aId)->increment('amount', $amount);
            DB::table('balances')->where('user_id', $orderBook->user_id)->where('ac_id', $aId)->increment('available', $amount);

            $this->saveTransaction($order, $orderBook, $amount, $price, 'sell');
            $this->updateOrderFill($orderBook, $amount);
            $this->updateOrderFill($order, $amount);

            OrderConfirm::dispatch($order, $price);

        }
    }
}