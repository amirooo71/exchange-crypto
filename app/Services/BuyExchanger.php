<?php
/**
 * Created by PhpStorm.
 * User: aparscoders
 * Date: 2/12/18
 * Time: 9:43 AM
 */

namespace App\Services;

use App\OrderSell;

class BuyExchanger
{

    public function process($orderBuy)
    {
        $orderSells = OrderSell::where('status', '=', 'in_progress')->orWhere('status', '=', 'partial')->get();
        foreach ($orderSells as $orderSell) {

            //If price was equals

            if ($orderSell->price === $orderBuy->price) {

                if ($orderSell->amount === $orderBuy->amount) {

                    //  status == confirm

                    $orderBuy->update(['status' => 'confirm']);
                    $orderSell->update(['status' => 'confirm']);

                } else {


                    if ($orderBuy->amount < $orderSell->amount) {
                        $totalAmount = ($orderSell->amount - $orderBuy->amount);
                        $orderSell->update(['amount' => $totalAmount]);
                        $orderBuy->update(['status' => 'confirm']);
                        $orderSell->update(['status' => 'partial']);
                    } else {
                        $totalAmount = ($orderBuy->amount - $orderSell->amount);
                        $orderBuy->update(['amount' => $totalAmount]);
                        $orderBuy->update(['status' => 'partial']);
                        $orderSell->update(['status' => 'confirm']);
                    }

                    // status == partial
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