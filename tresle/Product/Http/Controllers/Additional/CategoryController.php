<?php

namespace Tresle\Product\Http\Controllers\Additional;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Product\Model\Additional\Category;
use Tresle\Product\Http\Requests\ProductCategoriesRequest as Request;

class CategoryController extends Controller
{
    const NAO_ENCONTRADO = "Categoria não encontrada";

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Category[]
     */
    public function index()
    {
        return Category::with("additionals")->get();
    }

    /**
     * @param \Tresle\Product\Http\Requests\Additional\AdditionalCategoryRequest $request
     * @return mixed
     */
    public function store(\Tresle\Product\Http\Requests\Additional\AdditionalCategoryRequest $request)
    {
        $data = $request->all();
        return Category::create($data);
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        try {
            $category = Category::with("additionals")->findOrFail((int)$id);

            return [
                "error" => false,
                "message" => "",
                "data" => $category
            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => self::NAO_ENCONTRADO];
        }
    }

    /**
     * @param \Tresle\Product\Http\Requests\Additional\AdditionalCategoryRequest $request
     * @param $id
     * @return array
     */
    public function update(\Tresle\Product\Http\Requests\Additional\AdditionalCategoryRequest $request, $id)
    {
        try {
            $category = Category::findOrFail((int)$id);
            $data = $request->all();
            $category->update($data);
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => self::NAO_ENCONTRADO];
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
            $category = Category::findOrFail((int)$id);
            $category->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => self::NAO_ENCONTRADO];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir a categoria";
            $message = strpos($e->getMessage(), "delete") ? "{$mensagem}: Categoria associada a um adicional" : $mensagem;
            return ["error" => true, "message" => $message];
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function search(string $name)
    {
        $result = Category::where("name", "like", "%$name%")
            ->orderBy('name', 'ASC')
            ->where("status", true)->get()->toJson();

        return $result;
    }
}
