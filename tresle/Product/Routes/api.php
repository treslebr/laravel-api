<?php

$version = "v1";

Route::prefix("api/{$version}/products")->group(function() {
    Route::group([
        'middleware' => ['auth:api', 'admin']
    ], function() {

        // Product
        Route::post("/", "Product\ProductController@store");
        Route::delete("/{id}", "Product\ProductController@destroy");
        Route::put("/{id}", "Product\ProductController@update");
        Route::patch("/{id}", "Product\ProductController@update");

        Route::post("/{idProduct}/additional", "Product\ProductController@addAdditionalInProductById");
        Route::delete("/{idProduct}/additional", "Product\ProductController@removeAdditionalProductById");

        // image
        Route::post("/{idProduct}/image", "Product\ImageController@store");
        Route::delete("/{idProduct}/image/{idImage}", "Product\ImageController@destroy");

         // Categoria dos produtos adicionais
        Route::resource("/additional/category", "Additional\CategoryController");
        Route::get("/additional/category/q/{name}", "Additional\CategoryController@search");

        // Additional
        Route::resource("/additional", "Additional\AdditionalController");
        Route::get("/additional/q/{name}", "Additional\AdditionalController@search");

         // Product Category
        Route::get("/categories", "Product\CategoryController@index");
        Route::post("/categories", "Product\CategoryController@store");
        Route::get("/categories/{id}", "Product\CategoryController@show");
        Route::delete("/categories/{id}", "Product\CategoryController@destroy");
        Route::put("/categories/{id}", "Product\CategoryController@update");
        Route::patch("/categories/{id}", "Product\CategoryController@update");
        Route::get("/categories/q/{name}", "Product\CategoryController@search");
    });

    Route::get("/{id}", "Product\ProductController@show");
    Route::get("/", "Product\ProductController@index");
    Route::get("/q/{name}", "Product\ProductController@search");
});

Route::fallback(function(){
    return response()->json(['message' => 'Not Found!'], 404);
});
