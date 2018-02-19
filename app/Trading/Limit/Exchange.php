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