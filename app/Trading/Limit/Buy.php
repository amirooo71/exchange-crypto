<?php

namespace App\Trading\Limit;

use App\Balance;
use App\OrderSell;
use App\Trading\Limit\Exchange;

class Buy extends Exchange
{
    /**
     * @var OrderSell
     */
    private $orderSell;

    public function __construct(Balance $balance, OrderSell $orderSell)
    {
        parent::__construct($balance);
        $this->orderSell = $orderSell;
    }

    public function process($order)
    {
        foreach ($this->orderSell->orderBooks() as $orderBook) {

            if ($this->isFill($order)) {
                break;
            }

            if ($this->isPriceEqualsOrLess($order, $orderBook)) {

                if ($this->isAmountEquals($order, $orderBook)) {

                    $this->updateOrder($orderBook, $orderBook->amount, Exchange::STATUS_CONFIRMED);
                    $this->updateOrder($order, $order->amount, Exchange::STATUS_CONFIRMED);

                } else {

                    if ($this->isOrderBookAmountLess($order, $orderBook)) {

                        //do process

                    } else {

                        //do process

                    }
                }

            }
        }
    }
}