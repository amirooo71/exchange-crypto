<?php


use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trading', 'PagesController@trading');

Route::get('test', function () {


    return \App\Pair::where('asset_id',2)->where('currency_id',1)->first()->id;

});









