<?php


Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trade', 'PagesController@trade');


Route::get('test', function (\App\Services\BuyExchanger $exchanger) {


    $orderSell = \App\OrderSell::orderBy('price','asc')->first();

    dd($orderSell->price);


});

