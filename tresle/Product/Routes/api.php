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

        Route::post("/{idProduct}/additionals", "Product\ProductController@addAdditionalInProductById");
        Route::delete("/{idProduct}/additionals", "Product\ProductController@removeAdditionalProductById");

        // image
        Route::post("/{idProduct}/images", "Product\ImageController@store");
        Route::delete("/{idProduct}/images/{idImage}", "Product\ImageController@destroy");

         // Categoria dos produtos adicionais
        Route::resource("/additionals/categories", "Additional\CategoryController");
        Route::get("/additionals/categories/q/{name}", "Additional\CategoryController@search");

        // Additional
        Route::resource("/additionals", "Additional\AdditionalController");
        Route::get("/additionals/q/{name}", "Additional\AdditionalController@search");

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
    return response()->json(
        [
            'message' => 'Not Found',
            "errors" => ""
        ]
        , 404
    );
});
