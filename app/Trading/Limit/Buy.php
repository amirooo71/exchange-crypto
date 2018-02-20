<?php

namespace App\Trading\Limit;

use App\Transaction;

class Buy extends Exchange
{
    /**
     * @param $order
     */
    public function process($order)
    {
        foreach ($this->orderSell->orderBook() as $orderBook) {

            if ($this->isFill($order)) {
                break;
            }

            if ($this->isPriceEqualsOrLess($order, $orderBook)) {

                if ($this->isAmountEquals($order, $orderBook)) {

                    $this->tradingProcessOnEqualsAmount($order, $orderBook);

                } else {

                    if ($this->isOrderBookRemainAmountLess($order, $orderBook)) {

                        $this->tradingProcessOnLessOrderBookAmount($order, $orderBook);

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
    private function tradingProcessOnLessOrderBookAmount($order, $orderBook)
    {
        $this->tradingOrdersUpdateOnLessAmountBookAmount($order, $orderBook);
        $this->balanceCalculation($order, $orderBook);
        $this->saveTransaction($order, $orderBook);
    }

    /**
     * @param $order
     * @param $orderBook
     */
    private function balanceCalculation($order, $orderBook)
    {
        $BuyerBTCBalance = $this->getUserBalance($order->user_id, 2);
        $BuyerUSDBalance = $this->getUserBalance($order->user_id, 1);
        $SellerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
        $SellerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);

        $this->calculateBuyerBalance($order, $orderBook, $BuyerUSDBalance, $BuyerBTCBalance);
        $this->calculateSellerBalance($order, $orderBook, $SellerBTCBalance, $SellerUSDBalance);
        $this->calculateRemainAmountBalance($order, $orderBook, $BuyerUSDBalance);

    }

    /**
     * @param $order
     * @param $orderBook
     * @param $BuyerUSDBalance
     * @param $BuyerBTCBalance
     */
    private function calculateBuyerBalance($order, $orderBook, $BuyerUSDBalance, $BuyerBTCBalance)
    {

        $amount = $this->getBuyerAmount($order, $orderBook);

        $this->updateUserBalance($BuyerUSDBalance, [
            'amount' => $BuyerUSDBalance->amount - ($amount * $orderBook->price),
        ]);

        $this->updateUserBalance($BuyerBTCBalance, [
            'amount' => $BuyerBTCBalance->amount + $amount,
            'available' => $BuyerBTCBalance->available + $amount,
        ]);
    }

    /**
     * @param $order
     * @param $orderBook
     * @param $SellerBTCBalance
     * @param $SellerUSDBalance
     */
    private function calculateSellerBalance($order, $orderBook, $SellerBTCBalance, $SellerUSDBalance)
    {

        $this->updateUserBalance($SellerBTCBalance, [
            'amount' => $SellerBTCBalance->amount - $order->amount,
        ]);

        $this->updateUserBalance($SellerUSDBalance, [
            'amount' => $SellerUSDBalance->amount + ($order->amount * $orderBook->price),
            'available' => $SellerUSDBalance->available + ($order->amount * $orderBook->price),
        ]);

    }

    /**
     * @param $order
     * @param $orderBook
     * @param $BuyerUSDBalance
     */
    private function calculateRemainAmountBalance($order, $orderBook, $BuyerUSDBalance)
    {
        if ($this->isOrderBookPriceLess($order, $orderBook)) {

            $amount = $this->getBuyerAmount($order, $orderBook);

            $remainAmount = $amount * ($order->price - $orderBook->price);

            $this->updateUserBalance($BuyerUSDBalance, [
                'available' => $BuyerUSDBalance->available + $remainAmount,
            ]);

        }
    }

    /**
     * @param $order
     * @param $orderBook
     * @return mixed
     */
    private function getBuyerAmount($order, $orderBook)
    {
        if ($orderBook->amount < $order->amount) {
            $amount = $order->remainAmount();
        } else {
            $amount = $order->amount;
        }
        return $amount;
    }

    /**
     * @param $order
     * @param $orderBook
     */
    private function saveTransaction($order, $orderBook)
    {
        Transaction::create([
            'seller_id' => $orderBook->user_id,
            'buyer_id' => $order->user_id,
            'order_sale_id' => $orderBook->id,
            'order_buy_id' => $order->id,
        ]);
    }
}