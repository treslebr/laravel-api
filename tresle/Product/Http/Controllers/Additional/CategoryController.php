<?php

namespace Tresle\Product\Http\Controllers\Additional;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Product\Model\Additional\Category;
use Tresle\Product\Http\Requests\ProductCategoriesRequest as Request;

class CategoryController extends Controller
{

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Category[]
     */
    public function index()
    {
        return Category::with("additionals")->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
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
            $category = Category::findOrFail((int)$id)->with("additionals")->get();

            return [
                "error" => false,
                "message" => "",
                "data" => $category
            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Categoria não encontrada"];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return array
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
        try {
            $category = Category::findOrFail((int)$id);
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
