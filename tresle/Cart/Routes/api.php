<?php


$version = "v1";

Route::prefix("api/{$version}/cart")->group(function() {
    Route::group([
        'middleware' => ['auth:api', 'authCustomer']
    ], function() {
        Route::post('/', '\Tresle\Cart\Http\Controllers\CartController@store');
        Route::get('/', '\Tresle\Cart\Http\Controllers\CartController@index');
    });
});
