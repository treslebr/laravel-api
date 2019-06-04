<?php
Route::prefix("api/v1/products")->group(function() {
    Route::resource("/categories", "CategoriesController");
});
