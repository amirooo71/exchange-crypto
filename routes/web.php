<?php

use \Illuminate\Support\Facades\DB;

use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trading', 'PagesController@trading');

Route::get('test', function () {


    DB::update("UPDATE candles SET c=150, h=GREATEST(h, 150), l=LEAST(l, 125), count=count+1, v=v+ 2 WHERE id = 1");

});





// $q = "UPDATE x SET c=:price, h=GREATEST(h, :price), l=LEAST(l, :price), count=count+1, v=v+:amount WHERE t = :timestamp";


