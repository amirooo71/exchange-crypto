<?php


Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/trade', 'PagesController@trade');


Route::get('test', function () {

    $balance = \App\ Balance::where('user_id', '=', auth()->user()->id)->get();
    dd($balance);

});

