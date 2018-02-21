<?php

namespace App\Trading\Limit;


class Sell extends Exchange
{

    /**
     * @param $order
     */
    public function process($order)
    {

        foreach ($this->orderBuy->orderBook($order->price) as $orderBook) {

            if ($orderBook->user_id != $order->user_id) {

                if ($this->isFill($order)) {

                    break;

                }

                $SellerBTCBalance = $this->getUserBalance($order->user_id, 2);
                $SellerUSDBalance = $this->getUserBalance($order->user_id, 1);
                $BuyerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
                $BuyerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);

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

//                $remainAmount = $amount * ($price - $order->price);
//
//                if ($remainAmount != 0) {
//                    $this->updateUserBalance($BuyerUSDBalance, [
//                        'available' => $BuyerUSDBalance->available + $remainAmount,
//                    ]);
//                }

                $this->saveTransaction($order, $orderBook, $amount, $price, 'sell');

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
        $this->tradingOrdersUpdateOnEqualsAmount($order, $orderBook);
//        $this->balanceCalculation($order, $orderBook);
        $this->saveTransaction($order, $orderBook);
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
    protected function balanceCalculation($order, $orderBook)
    {
        $BuyerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
        $BuyerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);
        $SellerBTCBalance = $this->getUserBalance($order->user_id, 2);
        $SellerUSDBalance = $this->getUserBalance($order->user_id, 1);

        $this->calculateSellerBalance($order, $orderBook, $SellerUSDBalance, $SellerBTCBalance);
        $this->calculateBuyerBalance($order, $orderBook, $BuyerUSDBalance, $BuyerBTCBalance);
        $this->calculateRemainAmountBalance($order, $orderBook, $SellerUSDBalance);
    }

    /**
     * @param $order
     * @param $orderBook
     * @param $SellerUSDBalance
     * @param $SellerBTCBalance
     */
    protected function calculateSellerBalance($order, $orderBook, $SellerUSDBalance, $SellerBTCBalance)
    {

        $this->updateUserBalance($SellerBTCBalance, [
            'amount' => $SellerBTCBalance->amount - $orderBook->amount,
        ]);

        $this->updateUserBalance($SellerUSDBalance, [
            'amount' => $SellerUSDBalance->amount + ($amount * $orderBook->price),
            'available' => $SellerUSDBalance->available + ($amount * $orderBook->price),
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
        $this->updateUserBalance($BuyerBTCBalance, [
            'amount' => $BuyerBTCBalance->amount + $orderBook->amount,
            'available' => $BuyerBTCBalance->available + $orderBook->amount,
        ]);

        $this->updateUserBalance($BuyerUSDBalance, [
            'amount' => $BuyerUSDBalance->amount - ($orderBook->amount * $orderBook->price),
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


}