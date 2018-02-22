<?php

namespace App\Trading\Limit;

class Buy extends Exchange
{
    /**
     * @param $order
     */
    public function process($order)
    {
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

}