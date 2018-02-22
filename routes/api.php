<?php

use Illuminate\Http\Request;


Route::prefix('v1')->group(function () {

    /*
     |------------------------------------
     |              OrderBuy
     |------------------------------------
     */
    Route::post('/trade/orderbuy', 'Api\V1\OrderBuyController@store');
    Route::patch('/trade/orderbuy/{id}/update', 'Api\V1\OrderBuyController@update');
    Route::delete('/trade/orderbuy/{id}/delete', 'Api\V1\OrderBuyController@destroy');

    /*
     |------------------------------------
     |              OrderSell
     |------------------------------------
     */
    Route::patch('/trade/ordersell/{id}/update', 'Api\V1\OrderSellController@update');
    Route::delete('/trade/ordersell/{id}/delete', 'Api\V1\OrderSellController@destroy');
    Route::post('/trade/ordersell', 'Api\V1\OrderSellController@store');


    /**
     |------------------------------------
     | OrderBook & Balance & OrderHistory
     |------------------------------------
     */
    Route::get('/trade/user/orders/history', 'Api\V1\UserController@orders');
    Route::get('/trade/user/balance', 'Api\V1\BalanceController@userBalance');
    Route::get('/trade/orderbook/buy', 'Api\V1\OrderBookController@buyOrderBook');
    Route::get('/trade/orderbook/sell', 'Api\V1\OrderBookController@sellOrderBook');
});
