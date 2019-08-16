<?php

$version = "v1";

Route::prefix("api/{$version}/shipping")->group(function() {
    Route::get('/', '\Tresle\Shipping\Http\Controllers\ShippingController@index');
    Route::group([
        'middleware' => ['auth:api', 'admin']
    ], function() {
        Route::post('/', '\Tresle\Shipping\Http\Controllers\ShippingController@store');
        Route::put('/{id}', '\Tresle\Shipping\Http\Controllers\ShippingController@update');
        Route::patch('/{id}', '\Tresle\Shipping\Http\Controllers\ShippingController@update');
        Route::get('/{id}', '\Tresle\Shipping\Http\Controllers\ShippingController@show');
        Route::delete('/{id}', '\Tresle\Shipping\Http\Controllers\ShippingController@destroy');
    });

});
