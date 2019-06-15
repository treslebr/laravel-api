<?php

namespace Tresle\Product\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tresle\Product\Model\Product\Product;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
//        $data = $request->all();
//        return Product::create($data);
    }
}
