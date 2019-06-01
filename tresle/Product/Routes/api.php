<?php
Route::prefix("api/v1")->group(function() {
    Route::resource("/categories", "CategoriesController");
});
