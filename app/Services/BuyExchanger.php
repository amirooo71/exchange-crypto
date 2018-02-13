<?php

namespace App\Services;

use App\OrderSell;

class BuyExchanger
{

    public function process($orderBuy)
    {
        $orderSells = OrderSell::where('status', '=', 'in_progress')->orWhere('status', '=', 'partial')->get();
        foreach ($orderSells as $orderSell) {

            if ($orderSell->price === $orderBuy->price) {  // If price are equals

                if ($orderSell->amount === $orderBuy->amount) { // Is not partial

                    $orderBuy->update(['status' => 'confirmed']);
                    $orderSell->update(['status' => 'confirmed']);

                } else {

                    //  OrderBuy amount bigger than OrderSell amount

                    if ($orderBuy->amount < $orderSell->amount) {
                        $remainAmount = ($orderSell->amount - $orderBuy->amount);
                        $orderSell->update(['amount' => $remainAmount]);
                        $orderBuy->update(['status' => 'confirmed']);
                        $orderSell->update(['status' => 'partial']);
                    }

                    // OrderBuy amount smaller than OrderSell amount

                    if ($orderBuy->amount > $orderSell->amount) {
                        $remainAmount = ($orderBuy->amount - $orderSell->amount);
                        $orderBuy->update(['amount' => $remainAmount]);
                        $orderSell->update(['status' => 'confirmed']);
                        $orderBuy->update(['status' => 'partial']);
                    }

                }
            }


            //Amount will be check

            //if orders amounts were equals

            //fill

            //else

            //partial

            //done (send notification)


        }
    }

}