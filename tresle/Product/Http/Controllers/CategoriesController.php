<?php

namespace Tresle\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Product\Model\Categories;
use Tresle\Product\Http\Requests\ProductCategoriesRequest as Request;

class CategoriesController extends Controller
{

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Categories[]
     */
    public function index()
    {
        return Categories::all();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->all();
        return Categories::create($data);
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        try {
            $category = Categories::findOrFail((int)$id);
            return ["error" => false, "message" => "", "data" => $category];
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
            $category = Categories::findOrFail((int)$id);
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
            $category = Categories::findOrFail((int)$id);
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
        $result = Categories::where("name", "like", "%$name%")
            ->orderBy('name', 'ASC')
            ->where("status", true)->get()->toJson();

        return $result;
    }
}
