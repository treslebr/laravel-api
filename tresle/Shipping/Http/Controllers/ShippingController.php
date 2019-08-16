<?php


namespace Tresle\Shipping\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Shipping\Model\Shipping;

class ShippingController extends Controller
{

    /**
     * @param \Tresle\Shipping\Http\Requests\Shipping $request
     * @return array
     */
    public function store(\Tresle\Shipping\Http\Requests\Shipping $request)
    {
        try {
            $data = $request->all();
            $product = Shipping::create($data);
            return ["error" => false, "message" => "", "data" => $product];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Erro ao cadastrar o bairro"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o produto";
            return ["error" => true, "message" => $mensagem];
        }
    }

    /**
     * @param \Tresle\Shipping\Http\Requests\Shipping $request
     * @param $id
     * @return array
     */
    public function update(\Tresle\Shipping\Http\Requests\Shipping $request, $id)
    {
        try {
            $category = Shipping::findOrFail((int)$id);
            $data = $request->all();
            $category->update($data);
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Localidade não encontrada."];
        }
    }

}
