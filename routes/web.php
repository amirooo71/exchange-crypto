<?php


use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trade', 'PagesController@trade');


Route::get('test', function () {

    \Illuminate\Support\Facades\DB::table('balances')->where('id',1)->increment('amount',1000);

    return view('welcome');

});

