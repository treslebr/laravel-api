<?php

namespace Tresle\Product\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tresle\Product\Model\Product\Product;
use \Tresle\Product\Http\Requests\Product\ProductRequest;

class ProductController extends Controller
{
    /**
     * @var array
     */
    private $with = ["additionals", "category", "images"];

    const NAO_ENCONTRADO = "Produto não encontrado.";

    /**
     * @param ProductRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try {
            $data = $request->all();
            $product = Product::create($data);
            return $product;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar o produto."], 404);
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o produto";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Categoria não encontrada" : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $product = Product::findOrFail((int)$id);
            $product->delete();
            return ["error" => false, "message" => "Produto excluído."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        }
    }

    /**
     * @param Request $request
     * @param $idProduct
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function removeAdditionalProductById(Request $request, $idProduct){
        $idProduct  = (int)$idProduct;
        try {
            $additionalsId = $request->input("additionalsId");
            if(!$additionalsId)
                return response(["errors" => true, "message" => "O campo additionalsId é obrigatório."], 422);
            /** @var \Tresle\Product\Model\Product\Product $product */
            $product  = $this->getProductsWith()->findOrFail($idProduct);
            $product->additionals()->detach($additionalsId);
            return $this->getProductsWith()->findOrFail($idProduct);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao remover o produto adicional";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Adicional não encontrado" : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @param $idProduct
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function addAdditionalInProductById(Request $request, $idProduct){
        $idProduct  = (int)$idProduct;
        try {
            /** @var \Tresle\Product\Model\Product\Product $product */
            $product        = $this->getProductsWith()->findOrFail($idProduct);
            $additionals    = $product->additionals()->select(["product_additional_id"])->get()->toArray();
            $additionalsId  = $this->filterAdditionalsId($additionals, $request->input("additionalsId"));

            $product->additionals()->attach($additionalsId);

            return response($this->getProductsWith()->findOrFail($idProduct), 201);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o produto adicional";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Adicional não encontrado" : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $products = $this->getProductsWith()->get();
            return $products;
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return $this->getProductsWith()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param string $name
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(string $name)
    {
        try {
            $result = Product::where("name", "like", "%$name%")
                ->orderBy('name', 'ASC')
                ->with($this->with)
                ->where("status", true)->get();

            return $result;
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param ProductRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $product = $this->getProductsWith()->findOrFail((int)$id);
            $data = $request->all();
            $product->update($data);
            return $product;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao atualizar o produto";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Categoria não encontrada" : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
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
     * @param array $array
     * @return \Illuminate\Database\Eloquent\Builder|Product
     */
    private function getProductsWith($array = []){
        $array = !$array ? $this->with : $array;
        return Product::with($array);
    }
}
