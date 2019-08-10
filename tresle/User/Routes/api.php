<?php

$version = "v1";

Route::prefix("api/{$version}/user")->group(function() {
    Route::post("/", "\Tresle\User\Http\Auth\AuthController@store");
    Route::post("/login", "\Tresle\User\Http\Auth\AuthController@login");
});
