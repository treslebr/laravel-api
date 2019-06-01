<?php

namespace Tresle\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Tresle\Product\Model\Categories;

class ProductsController extends Controller
{
    public function index(){
        return Categories::all();
    }
}
