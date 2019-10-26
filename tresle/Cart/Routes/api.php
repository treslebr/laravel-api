<?php


$version = "v1";

Route::prefix("api/{$version}/carts")->group(function() {
    Route::group([
        'middleware' => ['auth:api', 'authCustomer']
    ], function() {
        Route::post('/', '\Tresle\Cart\Http\Controllers\CartController@store');
        Route::put('/{id}', '\Tresle\Cart\Http\Controllers\CartController@store');
        Route::patch('/{id}', '\Tresle\Cart\Http\Controllers\CartController@update');
        Route::get('/', '\Tresle\Cart\Http\Controllers\CartController@index');
        Route::delete('/{id}', '\Tresle\Cart\Http\Controllers\CartController@destroy');
    });
});
