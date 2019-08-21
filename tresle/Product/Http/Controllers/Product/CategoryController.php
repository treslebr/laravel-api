<?php

namespace Tresle\Product\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Product\Model\Product\Category;
use \Tresle\Product\Http\Requests\Product\ProductCategoriesRequest;

class CategoryController extends Controller
{
    const NAO_ENCONTRADO = "Categoria não encontrada";

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->getCategoryWith()->get();
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param ProductCategoriesRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(ProductCategoriesRequest $request)
    {
        try {
            $data = $request->all();
            return Category::create($data);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar a categoria."], 404);
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
            return $this->getCategoryWith()->findOrFail((int)$id);;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param ProductCategoriesRequest $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(ProductCategoriesRequest $request, $id)
    {
        try {
            $category = $this->getCategoryWith()->findOrFail((int)$id);
            $data = $request->all();
            $category->update($data);
            return ["error" => false, "message" => "Categoria atualizada."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(\Illuminate\Http\Request $request, $id)
    {
        try {
            $category = Category::findOrFail((int)$id);
            $category->delete();
            return ["error" => false, "message" => "Categoria excluída."];
        } catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir a categoria";
            $message = strpos($e->getMessage(), "delete") ? "{$mensagem}: Categoria associada a um produto." : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param string $name
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string
     */
    public function search(string $name)
    {
        try {
            $result = $this->getCategoryWith()->where("name", "like", "%$name%")
                ->orderBy('name', 'ASC')
                ->where("status", true)->get()->toJson();

            return $result;
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Product
     */
    private function getCategoryWith($array = ["products"]){
        return Category::with($array);
    }
}
