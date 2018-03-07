<?php


Auth::routes();

/*
 |------------------------------------
 |            Page Routes
 |------------------------------------
 */

Route::get('/', 'PagesController@landing');
Route::get('/trading', 'PagesController@trading');









