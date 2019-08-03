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
        $data = $request->all();
        return Product::create($data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Product[]
     */
    public function index()
    {
        $products = Product::with("additionals")->get();
        return $products;
    }

    /**
     * Retirando os adicionais que já estão relacionados ao produto;
     *
     * @param array $additionals
     * @param array $additionalsId
     * @return array
     */
    private function filterAdditionalsId($additionals, $additionalsId){
        $auxAdditionalsId = [];
        foreach ($additionalsId as $id){
            if(!in_array($id, array_column($additionals, "product_additional_id"))){
                $auxAdditionalsId[] = $id;
            }
        }
        return $auxAdditionalsId;
    }
}
