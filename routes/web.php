<?php


Auth::routes();

/*
 |------------------------------------
 |            Page Routes
 |------------------------------------
 */

Route::get('/', 'PagesController@landing');
Route::get('/trading', 'PagesController@trading')->middleware('auth');
Route::get('/home', 'PagesController@home');


Route::get('/verifyEmail/{token}', 'Auth\RegisterController@verify');


Route::get('t',function (){



    $original= 80;
    $current = 70;
    $diff = $current - $original;
    $more_less = $diff > 0 ? "More" : "Less";
    $percentChange = ($diff/$original)*100;
    echo "$percentChange% $more_less agaist $original";

});


Route::get('/test', function () {


    $order = \App\OrderSell::all();

    return count($order);


    $orders = \App\OrderBuy::all();

    $uniquePriceOrders = $orders->unique('price')->all();

    $orderBook = [];

    foreach ($uniquePriceOrders as $o) {
        $price = \App\OrderBuy::where('price', $o->price)->sum('price');
        $amount = \App\OrderBuy::where('price', $o->price)->sum('fill');
        $total = \App\OrderBuy::where('price', $o->price)->sum('amount');
        $percent = \App\OrderSell::where('price', $o->price)->get();
        $orderBook[] = serializer($price, $amount, $total);
    }

    return $orderBook;

});


