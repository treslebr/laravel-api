<?php

namespace Tresle\Product\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tresle\Product\Model\Product\Product;

class ProductController extends Controller
{
    private $with = ["additionals", "category"];

    /**
     * @param \Tresle\Product\Http\Requests\Product\ProductRequest $request
     * @return mixed
     */
    public function store(\Tresle\Product\Http\Requests\Product\ProductRequest $request)
    {
        try {
            $data = $request->all();
            $product = Product::create($data);
            return ["error" => false, "message" => "", "data" => $product];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Erro ao cadastrar o produto"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o produto";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Categoria não encontrada" : $mensagem;
            return ["error" => true, "message" => $message];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function destroy(\Illuminate\Http\Request $request, $id)
    {
        try {
            $product = Product::findOrFail((int)$id);
            $product->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Produto não encontrado"];
        }
    }

    /**
     * @param Request $request
     * @param $idProduct
     * @return array
     */
    public function removeAdditionalProductById(Request $request, $idProduct){
        $idProduct  = (int)$idProduct;
        try {
            /** @var \Tresle\Product\Model\Product\Product $product */
            $product  = $this->getProductsWith()->findOrFail($idProduct);
            $product->additionals()->detach($request->input("additionalsId"));

            return ["error" => false, "message" => "", "data" => $this->getProductsWith()->findOrFail($idProduct)];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Produto não encontrado"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao remover o produto adicional";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Adicional não encontrado" : $mensagem;
            return ["error" => true, "message" => $message];
        }
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
            $product        = $this->getProductsWith()->findOrFail($idProduct);
            $additionals    = $product->additionals()->select(["product_additional_id"])->get()->toArray();
            $additionalsId  = $this->filterAdditionalsId($additionals, $request->input("additionalsId"));

            $product->additionals()->attach($additionalsId);

            return ["error" => false, "message" => "", "data" => $this->getProductsWith()->findOrFail($idProduct)];
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
        $products = $this->getProductsWith()->get();
        return $products;
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        try {
            $product = $this->getProductsWith()->findOrFail($id);

            return [
                "error" => false,
                "message" => "",
                "data" => [
                    $product
                ],

            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Produto não encontrado"];
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function search(string $name)
    {
        $result = Product::where("name", "like", "%$name%")
            ->orderBy('name', 'ASC')
            ->with($this->with)
            ->where("status", true)->get();

        return $result;
    }

    /**
     * @param \Tresle\Product\Http\Requests\Product\ProductRequest $request
     * @param $id
     * @return array
     */
    public function update(\Tresle\Product\Http\Requests\Product\ProductRequest $request, $id)
    {
        try {
            $product = $this->getProductsWith()->findOrFail((int)$id);
            $data = $request->all();
            $product->update($data);
            return ["error" => false, "message" => "", "data" => $product];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Produto não encontrado"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao atualizar o produto";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Categoria não encontrada" : $mensagem;
            return ["error" => true, "message" => $message];
        }
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
        $additionalsId = !is_array($additionalsId) ? (array)$additionalsId : $additionalsId;
        foreach ($additionalsId as $id){
            if(!in_array($id, array_column($additionals, "product_additional_id"))){
                $auxAdditionalsId[] = $id;
            }
        }
        return $auxAdditionalsId;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Product
     */
    private function getProductsWith($array = []){
        $array = !$array ? $this->with : $array;
        return Product::with($array);
    }
}
