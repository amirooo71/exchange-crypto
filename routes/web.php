<?php


use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trading', 'PagesController@trading');
Route::post('trading/order/buy', 'OrderBuyController@store');

Route::get('test', function () {

    $order = \App\OrderBuy::find(1);

    dd($order->currency_id);


});

