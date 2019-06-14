<?php

/**
 * Categoria de produtos
 */
Route::prefix("api/v1/product/")->group(function() {
    Route::resource("category", "Product\CategoryController");
    Route::get("category/q/{name}", "Product\CategoryController@search");
});

/**
 * Categoria dos produtos adicionais
 */
Route::prefix("api/v1/product/additional/")->group(function() {
    Route::resource("category", "Additional\CategoryController");
    Route::get("category/q/{name}", "Additional\CategoryController@search");
});

/**
 * Produtos adicionais
 */
Route::prefix("api/v1/product/")->group(function() {
    Route::resource("additional", "Additional\AdditionalController");
    Route::get("additional/q/{name}", "Additional\AdditionalController@search");
});
