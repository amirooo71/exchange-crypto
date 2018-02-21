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

        $amount = $order->remainAmount();

        foreach ($this->orderSell->orderBook($order->price) as $orderBook) {

            if ($orderBook->user_id != $order->user_id) {

                if ($this->isFill($order)) {
                    break;
                }

                $BuyerBTCBalance = $this->getUserBalance($order->user_id, 2);
                $BuyerUSDBalance = $this->getUserBalance($order->user_id, 1);
                $SellerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
                $SellerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);

                $price = $orderBook->price;
                $amount = min($order->remainAmount(), $orderBook->remainAmount());

                $totalPrice = $amount * $price;

                $this->updateUserBalance($BuyerUSDBalance, [
                    'amount' => $BuyerUSDBalance->amount - $totalPrice
                ]);

                $this->updateUserBalance($BuyerBTCBalance, [
                    'amount' => $BuyerBTCBalance->amount + $amount,
                    'available' => $BuyerBTCBalance->available + $amount,
                ]);

                $this->updateUserBalance($SellerUSDBalance, [
                    'amount' => $SellerUSDBalance->amount + $totalPrice,
                    'available' => $SellerUSDBalance->available + $totalPrice,
                ]);

                $this->updateUserBalance($SellerBTCBalance, [
                    'amount' => $SellerBTCBalance->amount - $amount
                ]);

                $remainAmount = $amount * ($order->price - $price);

                if ($remainAmount != 0) {
                    $this->updateUserBalance($BuyerUSDBalance, [
                        'available' => $BuyerUSDBalance->available + $remainAmount,
                    ]);
                }

                $this->saveTransaction($order, $orderBook, $amount, $price, 'buy');

                $this->updateOrderFill($orderBook, $amount);
                $this->updateOrderFill($order, $amount);

            }
        }
    }

    /**
     * @param $order
     * @param $orderBook
     */
    private function tradingProcessOnEqualsAmount($order, $orderBook)
    {

    }

    /**
     * @param $order
     * @param $orderBook
     */
    private function tradingProcessOnLessOrderBookAmount($order, $orderBook)
    {
        $this->tradingOrdersUpdateOnLessAmountBookAmount($order, $orderBook);
//        $this->balanceCalculation($order, $orderBook);
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

        $this->updateUserBalance($BuyerUSDBalance, [
            'amount' => $BuyerUSDBalance->amount - ($orderBook->amount * $orderBook->price),
        ]);

        $this->updateUserBalance($BuyerBTCBalance, [
            'amount' => $BuyerBTCBalance->amount + $orderBook->amount,
            'available' => $BuyerBTCBalance->available + $orderBook->amount,
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
            'amount' => $SellerBTCBalance->amount - $orderBook->amount,
        ]);

        $this->updateUserBalance($SellerUSDBalance, [
            'amount' => $SellerUSDBalance->amount + ($orderBook->amount * $orderBook->price),
            'available' => $SellerUSDBalance->available + ($orderBook->amount * $orderBook->price),
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
     * @param $amount
     * @param $price
     * @param $type
     */
    private function saveTransaction($order, $orderBook, $amount, $price, $type)
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