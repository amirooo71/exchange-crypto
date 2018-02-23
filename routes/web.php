<?php


use App\Trading\Limit\Sell;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trading', 'PagesController@trading');

Route::get('test', function () {

    $orders = \App\Transaction::all();
    $dates = $orders->pluck('created_at');

    $datest = [];

    foreach ($dates->all() as $date) {
        $datest[] = $date->toDateTimeString();
    }

    dd($datest);

});






