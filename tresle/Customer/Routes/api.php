<?php

$version = "v1";

/**
 * Categoria dos produtos adicionais
 */
Route::prefix("api/{$version}/customer")->group(function() {
    Route::post("/", "\Tresle\Customer\Http\Auth\AuthController@store");
});
