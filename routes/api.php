<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

    Route::post('/trade/orderbuy', 'Api\V1\OrderBuyController@store');
    Route::patch('/trade/orderbuy/{id}/update', 'Api\V1\OrderBuyController@update');
    Route::patch('/trade/ordersell/{id}/update', 'Api\V1\OrderSellController@update');
    Route::delete('/trade/orderbuy/{id}/delete', 'Api\V1\OrderBuyController@destroy');
    Route::delete('/trade/ordersell/{id}/delete', 'Api\V1\OrderSellController@destroy');

    Route::post('/trade/ordersell', 'Api\V1\OrderSellController@store');
    Route::get('/orders/{user}/history', 'Api\V1\UserController@orderHistory');

});
