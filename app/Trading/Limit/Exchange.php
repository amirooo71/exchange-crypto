<?php

namespace App\Trading\Limit;

use App\Balance;
use App\OrderSell;
use App\OrderBuy;
use App\Transaction;

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
     * @param $fill
     * @param $status
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
    protected function isOrderBookRemainAmountLess($order, $orderBook)
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

    /**
     * @param $order
     * @param $orderBook
     */
    protected function tradingOrdersUpdateOnLessAmountBookAmount($order, $orderBook)
    {
        $this->updateOrder($orderBook, $orderBook->amount, Exchange::STATUS_CONFIRMED);
        $this->updateOrder($order, ($order->fill + $orderBook->amount), Exchange::STATUS_PARTIAL);
    }

    /**
     * @param $BuyerUSDBalance
     * @param $orderBook
     * @param $BuyerBTCBalance
     * @param $SellerUSDBalance
     * @param $SellerBTCBalance
     */
    protected function balanceCalculationOnEqualsAmount($BuyerUSDBalance, $orderBook, $BuyerBTCBalance, $SellerUSDBalance, $SellerBTCBalance): void
    {
        $this->updateUserBalance($BuyerUSDBalance, [
            'amount' => $BuyerUSDBalance->amount - ($orderBook->amount * $orderBook->price)
        ]);

        $this->updateUserBalance($BuyerBTCBalance, [
            'amount' => $BuyerBTCBalance->amount + $orderBook->amount,
            'available' => $BuyerBTCBalance->available + $orderBook->amount,
        ]);

        $this->updateUserBalance($SellerUSDBalance, [
            'amount' => $SellerUSDBalance->amount + ($orderBook->amount * $orderBook->price),
            'available' => $SellerUSDBalance->available + ($orderBook->amount * $orderBook->price),
        ]);

        $this->updateUserBalance($SellerBTCBalance, [
            'amount' => $SellerBTCBalance->amount - $orderBook->amount
        ]);
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