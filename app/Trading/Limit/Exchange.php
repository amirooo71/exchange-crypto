<?php

namespace App\Trading\Limit;

use App\Balance;
use App\OrderSell;
use App\OrderBuy;

class Exchange
{
    protected const STATUS_CONFIRMED = "confirmed";
    protected const STATUS_PARTIAL = "partial";

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
     * @param Balance $balance
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
     * @param $fill
     * @param $status
     */
    protected function updateOrder($order, $fill, $status)
    {
        $order->updateFill($fill);
        $order->updateStatus($status);
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
     * @return bool
     */
    protected function isPriceEqualsOrLess($order, $orderBook)
    {
        return $orderBook->price <= $order->price;
    }

    /**
     * @param $order
     * @param $orderBook
     * @return bool
     */
    protected function isPriceEqualsOrMore($order, $orderBook)
    {
        return $orderBook->price >= $order->price;
    }

    /**
     * @param $order
     * @param $orderBook
     * @return bool
     */
    protected function isAmountEquals($order, $orderBook)
    {
        return $orderBook->remainAmount() == $order->amount;
    }

    /**
     * @param $order
     * @param $orderBook
     * @return bool
     */
    protected function isOrderBookAmountLess($order, $orderBook)
    {
        return $orderBook->remainAmount() < $order->amount;
    }

    /**
     * @param $order
     * @param $orderBook
     * @return bool
     */
    protected function isOrderBookPriceLess($order, $orderBook)
    {
        return $orderBook->price < $order->price;
    }

    /**
     * @param $order
     * @param $orderBook
     * @return bool
     */
    protected function isOrderBookPriceMore($order, $orderBook)
    {
        return $orderBook->price > $order->price;
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
     */
    protected function tradingOrdersUpdateOnEqualsAmount($order, $orderBook)
    {
        $this->updateOrder($orderBook, $orderBook->amount, Exchange::STATUS_CONFIRMED);
        $this->updateOrder($order, $order->amount, Exchange::STATUS_CONFIRMED);
    }


}