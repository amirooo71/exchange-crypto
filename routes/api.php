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
    Route::post('/trade/orderbuy/{id}/edit', 'Api\V1\OrderBuyController@edit');

    Route::post('/trade/ordersell', 'Api\V1\OrderSellController@store');

    Route::get('/orders/{user}/history', 'Api\V1\UserController@orderHistory');

});
