<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('orders');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::resource('orders', 'OrderController');

Route::get('orders/{id}/resume', 'OrderController@resume');
Route::post('orders/{id}/payment', 'OrderController@payment');
Route::get('orders/{id}/checkPayment', 'OrderController@checkPayment');
Route::get('orders-admin', 'OrderController@ordersAdmin');
