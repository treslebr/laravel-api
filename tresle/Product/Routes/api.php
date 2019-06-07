<?php

/**
 * Categoria de produtos
 */
Route::prefix("api/v1/product/")->group(function() {
    Route::resource("category", "Product\CategoryController");
    Route::get("category/q/{name}", "Product\CategoryController@search");
});
