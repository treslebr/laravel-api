<?php

/**
 * Categoria de produtos
 */
Route::prefix("api/v1/product/category")->group(function() {
    Route::resource("/", "Product\CategoryController");
    Route::get("/q/{name}", "Product\CategoryController@search");
});
