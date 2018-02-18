<?php

namespace App\Services;

use App\Balance;
use App\OrderBuy;
use App\OrderSell;

class BuyExchanger
{

    const STATUS_CONFIRMED = "confirmed";
    const STATUS_PARTIAL = "partial";

    /**
     * @var OrderSell
     */
    protected $sell;

    /**
     * @var OrderBuy
     */
    protected $buy;

    /**
     * @var Balance
     */
    protected $balance;

    /**
     * BuyExchanger constructor.
     * @param OrderSell $orderSell
     * @param OrderBuy $orderBuy
     * @param Balance $balance
     */
    public function __construct(OrderSell $orderSell, OrderBuy $orderBuy, Balance $balance)
    {
        $this->sell = $orderSell;
        $this->buy = $orderBuy;
        $this->balance = $balance;
    }

    /**
     * @param $orderBuy
     */
    public function process($orderBuy)
    {
        foreach ($this->sell->getValidOrderSells() as $orderSell) {

            if ($orderBuy->status != 'confirmed') {

                if ($orderSell->price <= $orderBuy->price) {

                    if ($orderSell->remainAmount() == $orderBuy->amount) {

                        $this->updateOrderBuy($orderBuy, $orderBuy->amount, self::STATUS_CONFIRMED);
                        $this->updateOrderSell($orderSell, $orderSell->amount, self::STATUS_CONFIRMED);
                        $this->calculateUserBalance($orderBuy, $orderSell);

                    } else {

                        if ($orderBuy->amount < $orderSell->remainAmount()) {

                            $this->updateOrderBuy($orderBuy, $orderBuy->amount, self::STATUS_CONFIRMED);
                            $this->updateOrderSell($orderSell, ($orderSell->fill + $orderBuy->amount), self::STATUS_PARTIAL);
                            $this->calculateUserBalance($orderBuy, $orderSell);
                        } else {

                            $this->updateOrderBuy($orderBuy, ($orderBuy->fill + $orderSell->amount), self::STATUS_PARTIAL);
                            $this->updateOrderSell($orderSell, $orderSell->amount, self::STATUS_CONFIRMED);
                            $this->calculateUserBalance($orderBuy, $orderSell);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $orderBuy
     * @param $amount
     * @param $status
     */
    private function updateOrderBuy($orderBuy, $amount, $status)
    {
        $orderBuy->updateFill($amount);
        $orderBuy->updateStatus($status);
    }

    /**
     * @param $orderSell
     * @param $amount
     * @param $status
     */
    private function updateOrderSell($orderSell, $amount, $status)
    {
        $orderSell->updateFill($amount);
        $orderSell->updateStatus($status);
    }

    /**
     * @param $orderBuy
     * @param $orderSell
     */
    private function calculateUserBalance($orderBuy, $orderSell): void
    {
        $this->calculateUSDBalance($orderBuy, $orderSell);
        $this->calculateBTCBalance($orderBuy);
    }

    /**
     * @param $orderBuy
     * @param $orderSell
     */
    private function calculateUSDBalance($orderBuy, $orderSell): void
    {
        $USDBalance = $this->balance->getUserBalanceByUserId($orderBuy->user_id, 1);

        if ($orderSell->price < $orderBuy->price) {
            $remain = $orderBuy->amount * ($orderBuy->price - $orderSell->price);
            $USDBalance->update(['available' => ($USDBalance->available + $remain)]);
        }

        if ($orderBuy->amount > $orderSell->amount) {
            $amount = $orderBuy->fill;
        } else {
            $amount = $orderBuy->amount;
        }

        $amount = $USDBalance->amount - ($amount * $orderSell->price);
        $this->balance->updateBalance($orderBuy->user_id, $amount);
    }

    /**
     * @param $orderBuy
     */
    private function calculateBTCBalance($orderBuy): void
    {
        $BTCBalance = $this->balance->getUserBalanceByUserId($orderBuy->user_id, 2);
        $BTCBalance->update([
            'amount' => ($BTCBalance->amount + $orderBuy->amount),
            'available' => ($BTCBalance->amount + $orderBuy->amount),
        ]);
    }

}