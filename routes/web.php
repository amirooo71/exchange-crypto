<?php


use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trading', 'PagesController@trading');

Route::get('test', function () {


    $assetName = \App\Asset::find(1)->symbol;


    $name = \App\Currency::whereSymbol($assetName)->first();

    dd($name->id);

});






