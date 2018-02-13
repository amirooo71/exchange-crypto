<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trade', 'PagesController@trade');


Route::get('test', function (\App\Services\BuyExchanger $exchanger) {


    $balance = \App\Balance::where('user_id', '=', 1)->where('currency_id', '=', 1)->first();
    dd($balance->amount);

});