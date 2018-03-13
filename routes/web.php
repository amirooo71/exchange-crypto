<?php


Auth::routes();

/*
 |------------------------------------
 |            Page Routes
 |------------------------------------
 */

Route::get('/', 'PagesController@landing');
Route::get('/trading', 'PagesController@trading');
Route::get('/home', 'PagesController@home');


Route::get('/verifyEmail/{token}', 'Auth\RegisterController@verify');


Route::get('t', function () {


    $tr = \App\Transaction::where('created_at', '>=', \Carbon\Carbon::now()->subDay())->where('pair_id',1)->first();


    dd($tr);


});


Route::get('/test', function () {


    return \App\Ticker::first()->pair;


//    $order = \App\OrderSell::all();
//
//    return count($order);
//
//
//    $orders = \App\OrderBuy::all();
//
//    $uniquePriceOrders = $orders->unique('price')->all();
//
//    $orderBook = [];
//
//    foreach ($uniquePriceOrders as $o) {
//        $price = \App\OrderBuy::where('price', $o->price)->sum('price');
//        $amount = \App\OrderBuy::where('price', $o->price)->sum('fill');
//        $total = \App\OrderBuy::where('price', $o->price)->sum('amount');
//        $percent = \App\OrderSell::where('price', $o->price)->get();
//        $orderBook[] = serializer($price, $amount, $total);
//    }
//
//    return $orderBook;

});


