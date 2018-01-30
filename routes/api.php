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

    /**
     *********************************
     *       Public Routes
     *********************************
     */
    Route::get('/tickers', 'Api\V1\TradeController@getTickersInfo');

});
