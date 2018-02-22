<?php

namespace App\Trading\Limit;

use Illuminate\Support\Facades\DB;

class Sell extends Exchange
{

    /**
     * @param $order
     */
    public function process($order)
    {

        foreach ($this->orderBuy->orderBook($order->price) as $orderBook) {

            if ($this->isFill($order)) {

                break;

            }

            $price = $orderBook->price;
            $amount = min($order->remainAmount(), $orderBook->remainAmount());
            $totalPrice = $amount * $price;

            //Seller Balance Calculation
            DB::table('balances')->where('user_id', $order->user_id)->where('currency_id', 1)->increment('amount', $totalPrice);
            DB::table('balances')->where('user_id', $order->user_id)->where('currency_id', 1)->increment('available', $totalPrice);
            DB::table('balances')->where('user_id', $order->user_id)->where('currency_id', 2)->decrement('amount', $amount);

            //Buyer Balance Calculation
            DB::table('balances')->where('user_id', $orderBook->user_id)->where('currency_id', 1)->decrement('amount', $totalPrice);
            DB::table('balances')->where('user_id', $orderBook->user_id)->where('currency_id', 2)->increment('amount', $amount);
            DB::table('balances')->where('user_id', $orderBook->user_id)->where('currency_id', 2)->increment('available', $amount);

            $this->saveTransaction($order, $orderBook, $amount, $price, 'sell');
            $this->updateOrderFill($orderBook, $amount);
            $this->updateOrderFill($order, $amount);

        }
    }
}