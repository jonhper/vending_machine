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

// Get Products
Route::get('/products', 'ProductController@getProducts');
// Get Coins
Route::get('/coins', 'CoinController@getCoins');
// Get Order
Route::get('/order/{idOrder}', 'OrderController@getOrder');
// Post Order
Route::post('/order', 'OrderController@addOrder');
// Post order Payment
Route::post('/order/{idOrder}/payment', 'OrderController@paymentOrder');
// Get Order coins
Route::get('/ordercoins/{idOrder}', 'OrderCoinController@getOrderCoins');
// Post Order coins
Route::post('/ordercoins/{idOrder}', 'OrderCoinController@addOrderCoins');
// Delete Order coins
Route::get('/ordercoins/{idOrder}/delete', 'OrderCoinController@deleteOrderCoins');
