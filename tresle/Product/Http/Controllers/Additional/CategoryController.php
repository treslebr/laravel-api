<?php

namespace Tresle\Product\Http\Controllers\Additional;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Product\Model\Additional\Category;
use \Tresle\Product\Http\Requests\Additional\AdditionalCategoryRequest;

class CategoryController extends Controller
{
    const NAO_ENCONTRADO = "Categoria não encontrada";

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|Category[]
     */
    public function index()
    {
        try {
            return Category::with("additionals")->get();
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param AdditionalCategoryRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(AdditionalCategoryRequest $request)
    {
        try {
            $data = $request->all();
            $additional = Category::create($data);
            return $additional;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar a categoria."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response|Category|Category[]
     */
    public function show($id)
    {
        try {
            return Category::with("additionals")->findOrFail((int)$id);;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param AdditionalCategoryRequest $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(AdditionalCategoryRequest $request, $id)
    {
        try {
            $category = Category::findOrFail((int)$id);
            $data = $request->all();
            $category->update($data);
            return ["errors" => false, "message" => "Categoria atualizada."];
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
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir a categoria";
            $message = strpos($e->getMessage(), "delete") ? "{$mensagem}: Categoria associada a um adicional" : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
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
            $result = Category::where("name", "like", "%$name%")
                ->orderBy('name', 'ASC')
                ->where("status", true)->get()->toJson();

            return $result;
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }
}
