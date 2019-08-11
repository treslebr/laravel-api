<?php

$version = "v1";

Route::prefix("api/{$version}/customer")->group(function() {
    Route::post("/", "\Tresle\Customer\Http\CustomerController@store");
});
