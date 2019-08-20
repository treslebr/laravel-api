<?php

$version = "v1";

Route::prefix("api/{$version}/order")->group(function() {
    Route::group([
        'middleware' => ['auth:api', 'authCustomer']
    ], function() {
        Route::post('/', '\Tresle\Order\Http\Controllers\OrderController@store');
    });
});
