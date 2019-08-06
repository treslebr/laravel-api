<?php

$version = "v1";

/**
 * Categoria de produtos
 */
Route::prefix("api/{$version}/product/")->group(function() {
    Route::resource("category", "Product\CategoryController");
    Route::get("category/q/{name}", "Product\CategoryController@search");
});

/**
 * Categoria dos produtos adicionais
 */
Route::prefix("api/{$version}/product/additional/")->group(function() {
    Route::resource("category", "Additional\CategoryController");
    Route::get("category/q/{name}", "Additional\CategoryController@search");
});

/**
 * Produtos adicionais
 */
Route::prefix("api/{$version}/product/")->group(function() {
    Route::resource("additional", "Additional\AdditionalController");
    Route::get("additional/q/{name}", "Additional\AdditionalController@search");
});

/**
 * Produtos
 */
Route::prefix("api/{$version}/")->group(function() {
    Route::resource("product", "Product\ProductController");
    Route::post("product/{idProduct}/additional", "Product\ProductController@addAdditionalInProductById");
    Route::delete("product/{idProduct}/additional", "Product\ProductController@removeAdditionalProductById");
    Route::get("product/q/{name}", "Product\ProductController@search");
});

/**
 * Imagem do produto
 */
Route::prefix("api/{$version}/")->group(function() {
    Route::post("product/{idProduct}/image", "Product\ImageController@store");
});
