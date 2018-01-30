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

    /**
     *********************************
     *       Middleware Routes
     *********************************
     */
    Route::get('/user/balance', 'Api\V1\UserController@getBalance')->middleware('auth:api');
    Route::get('/user/order', 'Api\V1\UserController@getTradesHistory')->middleware('auth:api');
    Route::post('/exchange/order', 'Api\V1\ExchangeController@order')->middleware('auth:api');

    /**
     *********************************
     *       Public Routes
     *********************************
     */
    Route::get('/trade/tickers', 'Api\V1\TradeController@getTickersInfo');

});
