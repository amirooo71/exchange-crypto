<?php


use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trading', 'PagesController@trading');

Route::get('test', function () {


    $order = \App\Transaction::orderBy('created_at', 'desc')->first()->timestamp;
    return $order;

});








