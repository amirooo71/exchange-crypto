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

            if ($orderBook->user_id != $order->user_id) {

                if ($this->isFill($order)) {
                    break;
                }

                if ($this->isPriceEqualsOrLess($order, $orderBook)) {

                    if ($this->isAmountEquals($order, $orderBook)) {

                        $this->tradingOrdersUpdateOnEqualsAmount($order, $orderBook);
                        $this->saveTransaction($order, $orderBook);

                        $BuyerBTCBalance = $this->getUserBalance($order->user_id, 2);
                        $BuyerUSDBalance = $this->getUserBalance($order->user_id, 1);
                        $SellerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
                        $SellerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);

                        $this->updateUserBalance($BuyerUSDBalance, [
                            'amount' => $BuyerUSDBalance->amount - ($order->amount * $orderBook->price)
                        ]);

                        $this->updateUserBalance($BuyerBTCBalance, [
                            'amount' => $BuyerBTCBalance->amount + $order->amount,
                            'available' => $BuyerBTCBalance->available + $order->amount,
                        ]);

                        $this->updateUserBalance($SellerUSDBalance, [
                            'amount' => $SellerUSDBalance->amount + ($order->amount * $orderBook->price),
                            'available' => $SellerUSDBalance->available + ($order->amount * $orderBook->price),
                        ]);

                        $this->updateUserBalance($SellerBTCBalance, [
                            'amount' => $SellerBTCBalance->amount - $order->amount
                        ]);

                        if ($this->isOrderBookPriceLess($order, $orderBook)) {

                            $remainAmount = $order->amount * ($order->price - $orderBook->price);

                            $this->updateUserBalance($BuyerUSDBalance, [
                                'available' => $BuyerUSDBalance->available + $remainAmount,
                            ]);

                        }

                    } else {

                        if ($this->isOrderBookRemainAmountLess($order, $orderBook)) {

                            //$orderBook->remainAmount < $order->amount

                            $BuyerBTCBalance = $this->getUserBalance($order->user_id, 2);
                            $BuyerUSDBalance = $this->getUserBalance($order->user_id, 1);
                            $SellerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
                            $SellerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);

                            $this->updateUserBalance($BuyerUSDBalance, [
                                'amount' => $BuyerUSDBalance->amount - ($orderBook->remainAmount() * $orderBook->price)
                            ]);

                            $this->updateUserBalance($BuyerBTCBalance, [
                                'amount' => $BuyerBTCBalance->amount + $orderBook->remainAmount(),
                                'available' => $BuyerBTCBalance->available + $orderBook->remainAmount(),
                            ]);

                            $this->updateUserBalance($SellerUSDBalance, [
                                'amount' => $SellerUSDBalance->amount + ($orderBook->remainAmount() * $orderBook->price),
                                'available' => $SellerUSDBalance->available + ($orderBook->remainAmount() * $orderBook->price),
                            ]);

                            $this->updateUserBalance($SellerBTCBalance, [
                                'amount' => $SellerBTCBalance->amount - $orderBook->remainAmount()
                            ]);

                            if ($this->isOrderBookPriceLess($order, $orderBook)) {

                                $remainAmount = $orderBook->remainAmount() * ($order->price - $orderBook->price);

                                $this->updateUserBalance($BuyerUSDBalance, [
                                    'available' => $BuyerUSDBalance->available + $remainAmount,
                                ]);

                            }

                            $this->tradingProcessOnLessOrderBookAmount($order, $orderBook);

                        } else {

                            $BuyerBTCBalance = $this->getUserBalance($order->user_id, 2);
                            $BuyerUSDBalance = $this->getUserBalance($order->user_id, 1);
                            $SellerBTCBalance = $this->getUserBalance($orderBook->user_id, 2);
                            $SellerUSDBalance = $this->getUserBalance($orderBook->user_id, 1);


                            $this->updateUserBalance($BuyerUSDBalance, [
                                'amount' => $BuyerUSDBalance - ($order->remainAmout() * $orderBook->price),
                            ]);

                            $this->updateUserBalance($BuyerBTCBalance, [
                                'amount' => $BuyerBTCBalance->amount + $order->remainAmount(),
                                'available' => $BuyerBTCBalance->available + $order->remainAmount(),
                            ]);

                            $this->updateUserBalance($SellerUSDBalance, [
                                'amount' => $SellerUSDBalance->amount + ($order->remainAmount() * $orderBook->price),
                                'available' => $SellerUSDBalance->available + ($order->remainAmount() * $orderBook->price),
                            ]);

                            $this->updateUserBalance($SellerBTCBalance, [
                                'amount' => $SellerBTCBalance->amount - $order->remainAmount()
                            ]);

                            if ($this->isOrderBookPriceLess($order, $orderBook)) {

                                $remainAmount = $order->remainAmount() * ($order->price - $orderBook->price);

                                $this->updateUserBalance($BuyerUSDBalance, [
                                    'available' => $BuyerUSDBalance->available + $remainAmount,
                                ]);

                            }

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