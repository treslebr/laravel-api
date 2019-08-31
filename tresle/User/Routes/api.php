<?php

$version = "v1";

Route::prefix("api/{$version}/user")->group(function() {
    Route::post("/login", "\Tresle\User\Http\Auth\AuthController@login");

    Route::group([
        'middleware' => ['auth:api','admin']
    ], function() {
        Route::post("/", "\Tresle\User\Http\Auth\AuthController@store");
        Route::get('/logout', '\Tresle\User\Http\Auth\AuthController@logout');
        Route::get('/logged', '\Tresle\User\Http\Auth\AuthController@getUserLogged');
        Route::delete('/{id}', '\Tresle\User\Http\Auth\AuthController@destroy');
    });

});
