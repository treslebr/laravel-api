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

}
