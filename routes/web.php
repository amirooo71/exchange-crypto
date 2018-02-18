<?php


Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trade', 'PagesController@trade');


Route::get('test', function (\App\Services\BuyExchanger $exchanger) {


    $order = \App\OrderBuy::find(2);

    $exchanger->process($order);

});

