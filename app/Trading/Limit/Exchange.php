<?php

namespace App\Trading\Limit;

use App\Balance;
use App\OrderSell;
use App\OrderBuy;
use App\Transaction;

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
     * @param $balance
     * @param array $data
     */
    protected function updateUserBalance($balance, $data = [])
    {
        $balance->update($data);
    }

    /**
     * @param $userId
     * @param $currencyId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    protected function getUserBalance($userId, $currencyId)
    {
        return $this->balance->getUserBalanceByUserId($userId, $currencyId);
    }

    /**
     * @param $order
     * @param $orderBook
     * @param $amount
     * @param $price
     * @param $type
     */
    protected function saveTransaction($order, $orderBook, $amount, $price, $type)
    {
        Transaction::create([
            'seller_id' => $orderBook->user_id,
            'buyer_id' => $order->user_id,
            'order_sale_id' => $orderBook->id,
            'order_buy_id' => $order->id,
            'amount' => $amount,
            'price' => $price,
            'status' => $type,
        ]);
    }
}