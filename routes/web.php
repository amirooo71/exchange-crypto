<?php


use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trade', 'PagesController@trade');


Route::get('test', function (Sell $sell) {


    $orderSell = new \App\OrderSell();

    $orders = $orderSell->orderBook(980);

    dd($orders);
});

