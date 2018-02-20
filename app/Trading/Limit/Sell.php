<?php

namespace App\Trading\Limit;

use App\Transaction;

class Sell extends Exchange
{

    /**
     * @param $order
     */
    public function process($order)
    {
        foreach ($this->orderBuy->orderBook() as $orderBook) {

            if ($this->isFill($order)) {

                break;

            }

            if ($this->isPriceEqualsOrMore($order, $orderBook)) {

                if ($this->isAmountEquals($order, $orderBook)) {

                    $this->tradingProcessOnEqualsAmount($order, $orderBook);

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

    /**
     * @param $order
     * @param $orderBook
     */
    private function tradingProcessOnEqualsAmount($order, $orderBook)
    {
        $this->tradingOrdersUpdateOnEqualsAmount($order, $orderBook);
        $this->balanceCalculation($order, $orderBook);
        $this->saveTransaction($order, $orderBook);
    }

    /**
     * @param $order
     * @param $orderBook
     */
    protected function balanceCalculation($order, $orderBook)
    {
        $BuyerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
        $BuyerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);
        $SellerBTCBalance = $this->getUserBalance($order->user_id, 2);
        $SellerUSDBalance = $this->getUserBalance($order->user_id, 1);

        $this->calculateSellerBalance($order, $SellerUSDBalance, $SellerBTCBalance);
        $this->calculateBuyerBalance($order, $orderBook, $BuyerUSDBalance, $BuyerBTCBalance);
        $this->calculateRemainAmountBalance($order, $orderBook, $SellerUSDBalance);
    }

    /**
     * @param $order
     * @param $SellerUSDBalance
     * @param $SellerBTCBalance
     */
    protected function calculateSellerBalance($order, $SellerUSDBalance, $SellerBTCBalance)
    {
        $this->updateUserBalance($SellerUSDBalance, [
            'amount' => $SellerUSDBalance->amount + ($order->amount * $order->price),
            'available' => $SellerUSDBalance->available + ($order->amount * $order->price),
        ]);

        $this->updateUserBalance($SellerBTCBalance, [
            'amount' => $SellerBTCBalance->amount - $order->amount,
        ]);
    }

    /**
     * @param $order
     * @param $orderBook
     * @param $BuyerUSDBalance
     * @param $BuyerBTCBalance
     */
    protected function calculateBuyerBalance($order, $orderBook, $BuyerUSDBalance, $BuyerBTCBalance)
    {
        $this->updateUserBalance($BuyerUSDBalance, [
            'amount' => $BuyerUSDBalance->amount - ($order->amount * $orderBook->price),
        ]);

        $this->updateUserBalance($BuyerBTCBalance, [
            'amount' => $BuyerBTCBalance->amount + $order->amount,
            'available' => $BuyerBTCBalance->available + $order->amount,
        ]);
    }

    /**
     * @param $order
     * @param $orderBook
     * @param $SellerUSDBalance
     */
    protected function calculateRemainAmountBalance($order, $orderBook, $SellerUSDBalance)
    {
        if ($this->isOrderBookPriceMore($order, $orderBook)) {

            $remainAmount = $order->amount * ($orderBook->price - $order->price);

            $this->updateUserBalance($SellerUSDBalance, [
                'amount' => $SellerUSDBalance->amount + $remainAmount,
                'available' => $SellerUSDBalance->available + $remainAmount,
            ]);

        }
    }

    /**
     * @param $order
     * @param $orderBook
     */
    private function saveTransaction($order, $orderBook)
    {
        Transaction::create([
            'seller_id' => $order->user_id,
            'buyer_id' => $orderBook->user_id,
            'order_sale_id' => $order->id,
            'order_buy_id' => $orderBook->id,
        ]);
    }


}