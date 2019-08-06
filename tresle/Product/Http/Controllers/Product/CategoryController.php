<?php

namespace Tresle\Product\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Product\Model\Product\Category;
use Tresle\Product\Http\Requests\ProductCategoriesRequest as Request;

class CategoryController extends Controller
{

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Category[]
     */
    public function index()
    {
        return $this->getCategoryWith()->get();
    }

    /**
     * @param \Tresle\Product\Http\Requests\Product\ProductCategoriesRequest $request
     * @return mixed
     */
    public function store(\Tresle\Product\Http\Requests\Product\ProductCategoriesRequest $request)
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
            $category = $this->getCategoryWith()->findOrFail((int)$id);
            return ["error" => false, "message" => "", "data" => $category];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Categoria não encontrada"];
        }
    }

    /**
     * @param \Tresle\Product\Http\Requests\Product\ProductCategoriesRequest $request
     * @param $id
     * @return array
     */
    public function update(\Tresle\Product\Http\Requests\Product\ProductCategoriesRequest $request, $id)
    {
        try {
            $category = $this->getCategoryWith()->findOrFail((int)$id);
            $data = $request->all();
            $category->update($data);
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Categoria não encontrada"];
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
            return ["error" => true, "message" => "Categoria não encontrada"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir a categoria";
            $message = strpos($e->getMessage(), "delete") ? "{$mensagem}: Categoria associada a um produto" : $mensagem;
            return ["error" => true, "message" => $message];
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function search(string $name)
    {
        $result = $this->getCategoryWith()->where("name", "like", "%$name%")
            ->orderBy('name', 'ASC')
            ->where("status", true)->get()->toJson();

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|Product
     */
    private function getCategoryWith($array = ["products"]){
        return Category::with($array);
    }
}
