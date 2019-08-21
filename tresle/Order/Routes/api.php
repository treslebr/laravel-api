<?php

$version = "v1";

Route::prefix("api/{$version}/order")->group(function() {
    Route::group([
        'middleware' => ['auth:api', 'authCustomer']
    ], function() {
        Route::post('/', '\Tresle\Order\Http\Controllers\OrderController@store');
    });

    Route::group([
        'middleware' => ['auth:api', 'admin']
    ], function() {
        Route::put('/{id}', '\Tresle\Order\Http\Controllers\OrderController@update');
        Route::get('/{id}', '\Tresle\Order\Http\Controllers\OrderController@show');
        Route::get('/', '\Tresle\Order\Http\Controllers\OrderController@index');
    });
});

Route::prefix("api/{$version}/customer/order")->group(function() {
    Route::group([
        'middleware' => ['auth:api', 'authCustomer']
    ], function() {
        Route::get('/', '\Tresle\Order\Http\Controllers\OrderController@getOrderCustomerLogged');
        Route::get('/{id}', '\Tresle\Order\Http\Controllers\OrderController@getOrderCustomerLoggedById');
    });
});
