<?php

$version = "v1";

Route::prefix("api/{$version}/customer")->group(function() {
    Route::post("/", "\Tresle\Customer\Http\Controllers\CustomerController@store");
    Route::post("/login", "\Tresle\User\Http\Auth\AuthController@login");

    Route::group([
        'middleware' => ['auth:api', 'authCustomer']
    ], function() {
        Route::get('/logout', '\Tresle\User\Http\Auth\AuthController@logout');
        Route::get('/logged', '\Tresle\User\Http\Auth\AuthController@getUserLogged');
    });
});

Route::prefix("api/{$version}/customer/{idCustomer}/address")->group(function() {
    Route::group([
        'middleware' => ['auth:api', 'admin']
    ], function() {
        Route::post("/", "\Tresle\Customer\Http\Controllers\CustomerAddressController@store");
    });
});

Route::prefix("api/{$version}/customer/address")->group(function() {
    Route::group([
        'middleware' => ['auth:api', 'authCustomer']
    ], function() {
        Route::post("/", "\Tresle\Customer\Http\Controllers\CustomerAddressController@addAddressCustomerLogged");
    });
});
