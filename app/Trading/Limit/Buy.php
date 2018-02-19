<?php

namespace App\Trading\Limit;

use App\Balance;
use App\OrderSell;

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
        foreach ($this->orderSell->getSortedOrders() as $orderBook) {
        }
    }
}