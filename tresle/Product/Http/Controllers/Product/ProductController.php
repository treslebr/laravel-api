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
     * @param Request $request
     * @param int $idProduct
     * @return mixed
     */
    public function addAdditionalInProductById(Request $request, $idProduct){
        $idProduct  = (int)$idProduct;
        try {
            /** @var \Tresle\Product\Model\Product\Product $product */
            $product        = Product::with("additionals")->findOrFail($idProduct);
            $additionals    = $product->additionals()->select(["product_additional_id"])->get()->toArray();
            $additionalsId  = $this->filterAdditionalsId($additionals, $request->input("additionalsId"));

            $product->additionals()->attach($additionalsId);

            return ["error" => false, "message" => "", "data" => $product];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Produto não encontrado"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o produto adicional";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Adicional não encontrado" : $mensagem;
            return ["error" => true, "message" => $message];
        }
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
