<?php

$version = "v1";

Route::prefix("api/{$version}/product")->group(function() {
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

        // Additional
        Route::resource("/additional", "Additional\AdditionalController");
        Route::get("/additional/q/{name}", "Additional\AdditionalController@search");

         // Categoria dos produtos adicionais
        Route::resource("/additional/category", "Additional\CategoryController");
        Route::get("/additional/category/q/{name}", "Additional\CategoryController@search");

         // Product Category
        Route::get("/category", "Product\CategoryController@index");
        Route::post("/category", "Product\CategoryController@store");
        Route::get("/category/{id1}", "Product\CategoryController@show");
        Route::delete("/category", "Product\CategoryController@destroy");
        Route::put("/category/{id}", "Product\CategoryController@update");
        Route::patch("/category/{id}", "Product\CategoryController@update");
        Route::get("/category/q/{name}", "Product\CategoryController@search");
    });

    Route::get("/{id}", "Product\ProductController@show");
    Route::get("/", "Product\ProductController@index");
    Route::get("/q/{name}", "Product\ProductController@search");
});

Route::fallback(function(){
    return response()->json(['message' => 'Not Found!'], 404);
});
