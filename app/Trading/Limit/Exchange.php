<?php

namespace App\Trading\Limit;

use App\Balance;

class Exchange
{
    protected const STATUS_CONFIRMED = "confirmed";
    protected const STATUS_PARTIAL = "partial";

    /**
     * @var Balance
     */
    private $balance;

    /**
     * Exchange constructor.
     * @param Balance $balance
     */
    public function __construct(Balance $balance)
    {
        $this->balance = $balance;
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

    protected function sellerBalanceCalculation()
    {
    }

    protected function buyerBalanceCalculation()
    {
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
        return $orderBook->amount < $order->amount;
    }

    /**
     * @param $balance
     * @param array $data
     */
    private function updateUserBalance($balance, $data = [])
    {
        $balance->update($data);
    }

    /**
     * @param $userId
     * @param $currencyId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    private function getUserBalance($userId, $currencyId)
    {
        return $this->balance->getUserBalanceByUserId($userId, $currencyId);
    }

}