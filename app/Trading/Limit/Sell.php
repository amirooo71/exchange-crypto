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

            if ($orderBook->user_id != $order->user_id) {

                if ($this->isFill($order)) {

                    break;

                }

                if ($this->isPriceEqualsOrMore($order, $orderBook)) {

                    if ($this->isAmountEquals($order, $orderBook)) {

                        $this->tradingProcessOnEqualsAmount($order, $orderBook);

                        $BuyerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
                        $BuyerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);
                        $SellerBTCBalance = $this->getUserBalance($order->user_id, 2);
                        $SellerUSDBalance = $this->getUserBalance($order->user_id, 1);

                        $this->updateUserBalance($SellerUSDBalance, [
                            'amount' => $SellerUSDBalance->amount + ($order->amount * $orderBook->price),
                            'available' => $SellerUSDBalance->available + ($order->amount * $orderBook->price),
                        ]);

                        $this->updateUserBalance($SellerBTCBalance, [
                            'amount' => $SellerBTCBalance->amount - $order->amount
                        ]);

                        $this->updateUserBalance($BuyerUSDBalance, [
                            'amount' => $BuyerUSDBalance->amount - ($order->amount * $orderBook->price)
                        ]);

                        $this->updateUserBalance($BuyerBTCBalance, [
                            'amount' => $BuyerBTCBalance->amount + $order->amount,
                            'available' => $BuyerBTCBalance->available + $order->amount,
                        ]);

                    } else {

                        if ($this->isOrderBookRemainAmountLess($order, $orderBook)) {

                            $BuyerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
                            $BuyerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);
                            $SellerBTCBalance = $this->getUserBalance($order->user_id, 2);
                            $SellerUSDBalance = $this->getUserBalance($order->user_id, 1);

                            $this->updateUserBalance($SellerUSDBalance, [
                                'amount' => $SellerUSDBalance->amount + ($orderBook->remainAmount() * $orderBook->price),
                                'available' => $SellerUSDBalance->available + ($orderBook->remainAmount() * $orderBook->price),
                            ]);

                            $this->updateUserBalance($SellerBTCBalance, [
                                'amount' => $SellerBTCBalance->amount - $orderBook->remainAmount()
                            ]);

                            $this->updateUserBalance($BuyerUSDBalance, [
                                'amount' => $BuyerUSDBalance->amount - ($orderBook->remainAmount() * $orderBook->price)
                            ]);

                            $this->updateUserBalance($BuyerBTCBalance, [
                                'amount' => $BuyerBTCBalance->amount + $orderBook->remainAmount(),
                                'available' => $BuyerBTCBalance->available + $orderBook->remainAmount(),
                            ]);

                            $this->tradingProcessOnLessOrderBookAmount($order, $orderBook);

                        } else {

                            $BuyerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
                            $BuyerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);
                            $SellerBTCBalance = $this->getUserBalance($order->user_id, 2);
                            $SellerUSDBalance = $this->getUserBalance($order->user_id, 1);

                            $this->updateUserBalance($SellerUSDBalance, [
                                'amount' => $SellerUSDBalance->amount + ($order->remainAmount() * $orderBook->price),
                                'available' => $SellerUSDBalance->available + ($order->remainAmount() * $orderBook->price),
                            ]);

                            $this->updateUserBalance($SellerBTCBalance, [
                                'amount' => $SellerBTCBalance->amount - $order->remainAmount()
                            ]);

                            $this->updateUserBalance($BuyerUSDBalance, [
                                'amount' => $BuyerUSDBalance->amount - ($order->remainAmount() * $orderBook->price)
                            ]);

                            $this->updateUserBalance($BuyerBTCBalance, [
                                'amount' => $BuyerBTCBalance->amount + $order->remainAmount(),
                                'available' => $BuyerBTCBalance->available + $order->remainAmount(),
                            ]);

                            $this->updateOrder($orderBook, ($orderBook->fill + $order->amount), Exchange::STATUS_PARTIAL);
                            $this->updateOrder($order, $order->amount, Exchange::STATUS_CONFIRMED);
                            $this->saveTransaction($order, $orderBook);

                        }

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
            'amount' => $order->amount,
            'price' => $order->price,
            'status' => 'buy',
        ]);
    }

}