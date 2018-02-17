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

            if ($orderSell->price <= $orderBuy->price) {
                
                if ($orderSell->remainAmount() == $orderBuy->amount) {

                    $this->updateOrderBuy($orderBuy, $orderBuy->amount, self::STATUS_CONFIRMED);
                    $this->updateOrderSell($orderSell, $orderSell->amount, self::STATUS_CONFIRMED);
                    $this->calculateUserBalance($orderBuy);

                } else {

                    if ($orderBuy->amount < $orderSell->remainAmount()) {

                        $this->updateOrderBuy($orderBuy, $orderBuy->amount, self::STATUS_CONFIRMED);
                        $this->updateOrderSell($orderSell, ($orderSell->amount - $orderBuy->amount), self::STATUS_PARTIAL);
                        $this->calculateUserBalance($orderBuy);
                    }

                    if ($orderBuy->amount > $orderSell->remainAmount()) {

                        $this->updateOrderBuy($orderBuy, ($orderBuy->amount - $orderSell->amount), self::STATUS_PARTIAL);
                        $this->updateOrderSell($orderSell, $orderSell->amount, self::STATUS_CONFIRMED);
                        $this->calculateUserBalance($orderBuy);
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
     */
    private function calculateUserBalance($orderBuy): void
    {
        $this->calculateUSDBalance($orderBuy);
        $this->calculateBTCBalance($orderBuy);
    }

    /**
     * @param $orderBuy
     */
    private function calculateUSDBalance($orderBuy): void
    {
        $USDBalance = $this->balance->getUserBalanceByUserId($orderBuy->user_id, 1);
        $amount = ($USDBalance->amount - ($orderBuy->amount * $orderBuy->price));
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

    /**
     * @param $orderBuy
     * @param $orderSell
     */
    private function computedCheaperPrice($orderBuy, $orderSell): void
    {
        if ($orderSell->price < $orderBuy->price) {
            $remainPrice = ($orderBuy->amount * ($orderBuy->price - $orderSell->price));
            $USDBalance = $this->balance->getUserBalanceByUserId($orderBuy->user_id, 1);
            $USDBalance->update([
                'amount' => ($USDBalance->amount + $remainPrice),
                'available' => ($USDBalance->available + $remainPrice),
            ]);
        }
    }

}