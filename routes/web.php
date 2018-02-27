<?php


use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trading', 'PagesController@trading');

Route::get('test', function () {

    \App\Events\OrderBook::dispatch('Amir Shojaei');
    \App\Events\OrderConfirm::dispatch(15000,145000);

    return 'Done';


});






