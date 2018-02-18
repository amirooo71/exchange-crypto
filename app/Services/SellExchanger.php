<?php

namespace App\Services;

use App\Balance;
use App\OrderBuy;
use App\OrderSell;

class SellExchanger
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
     * SellExchanger constructor.
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
     * @param $orderSell
     */
    public function process($orderSell)
    {
        foreach ($this->buy->getValidOrderBuys() as $orderBuy) {

            if ($orderSell->status != 'confirmed') {

                if ($orderBuy->price >= $orderSell->price) {

                    if ($orderBuy->remainAmount() == $orderSell->amount) {

                        $this->updateOrderBuy($orderBuy, $orderBuy->amount, self::STATUS_CONFIRMED);
                        $this->updateOrderSell($orderSell, $orderSell->amount, self::STATUS_CONFIRMED);
                        $this->calculateUserBalance($orderBuy, $orderSell);

                    } else {

                        if ($orderSell->amount < $orderBuy->remainAmount()) {

                            $this->updateOrderSell($orderSell, $orderSell->amount, self::STATUS_CONFIRMED);
                            $this->updateOrderBuy($orderBuy, ($orderBuy->fill + $orderSell->amount), self::STATUS_PARTIAL);
                            $this->calculateUserBalance($orderBuy, $orderSell);

                        } else {

                            $this->updateOrderSell($orderSell, ($orderBuy->fill + $orderSell->amount), self::STATUS_PARTIAL);
                            $this->updateOrderBuy($orderBuy, $orderBuy->amount, self::STATUS_CONFIRMED);
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
        $this->calculateBTCBalance($orderBuy, $orderSell);
    }

    /**
     * @param $orderBuy
     * @param $orderSell
     */
    private function calculateUSDBalance($orderBuy, $orderSell): void
    {
        $USDBalance = $this->balance->getUserBalanceByUserId($orderBuy->user_id, 1);

        if ($orderSell->price > $orderBuy->price) {
            $remain = $orderSell->amount * ($orderSell->price - $orderSell->price);
            $USDBalance->update(['available' => ($USDBalance->available + $remain)]);
        }
//
//        if ($orderSell->amount < $orderBuy->amount) {
//            $amount = $orderSell->fill;
//        } else {
//            $amount = $orderSell->amount;
//        }

        $amount = ($USDBalance->amount + ($orderSell->amount * $orderSell->price));
        $available = ($USDBalance->amount + ($orderSell->amount * $orderSell->price));
        $this->balance->updateBalanceOnSellAction($orderBuy->user_id, $amount, $available);
    }

    /**
     * @param $orderBuy
     * @param $orderSell
     */
    private function calculateBTCBalance($orderBuy, $orderSell): void
    {
        $BTCBalance = $this->balance->getUserBalanceByUserId($orderBuy->user_id, 2);

        if ($orderSell->amount < $orderBuy->amount) {
            $amount = $orderSell->fill;
        } else {
            $amount = $orderSell->amount;
        }


        $BTCBalance->update([
            'amount' => $BTCBalance->amount - $amount,
        ]);
    }


}