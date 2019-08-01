<?php

namespace Tresle\Product\Http\Controllers\Additional;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Product\Model\Additional\Additional;
use Tresle\Product\Http\Requests\ProductCategoriesRequest as Request;

class AdditionalController extends Controller
{
    const NAO_ENCONTRADO = "Produto adicional não encontrado";

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Additional[]
     */
    public function index()
    {
        return Additional::with(["category", "products"])->get();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->all();
        return Additional::create($data);
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        try {
            $additional = Additional::with(["category", "products"])->findOrFail($id);

            return [
                "error" => false,
                "message" => "",
                "data" => [
                    $additional
                ],

            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => self::NAO_ENCONTRADO];
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
            $additional = Additional::findOrFail((int)$id);
            $data = $request->all();
            $additional->update($data);
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
            $additional = Additional::findOrFail((int)$id);
            $additional->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => self::NAO_ENCONTRADO];
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function search(string $name)
    {
        $result = Additional::where("name", "like", "%$name%")
            ->orderBy('name', 'ASC')
            ->with("category")
            ->where("status", true)->get();

        return $result;
    }
}
