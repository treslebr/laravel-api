<?php

namespace Modules\Core\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;


class ProductServiceProvider extends ServiceProvider
{
    /**
     * Utilizado quando a aplicacao eh iniciada
     */
    public function boot()
    {
        Route::namespace("Modules\Core\Product\Http\Controllers")
            ->group(__DIR__."/../Routes/web.php");
    }

    /**
     * Utilizada quando a aplicacao eh registrada
     */
    public function register()
    {
        parent::register(); // TODO: Change the autogenerated stub
    }
}
