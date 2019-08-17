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
            $shipping = Shipping::create($data);
            return ["error" => false, "message" => "", "data" => $shipping];
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
            $shipping = Shipping::findOrFail((int)$id);
            $data = $request->all();
            $shipping->update($data);
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Localidade não encontrada."];
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        try {
            $shipping = Shipping::findOrFail((int)$id);

            return [
                "error" => false,
                "message" => "",
                "data" => $shipping
            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Localidade não encontrada."];
        }
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return Shipping::get();
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
            $shipping = Shipping::findOrFail((int)$id);
            $shipping->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Localidade não encontrada."];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir a localidade";
            $message = strpos($e->getMessage(), "delete") ? "{$mensagem}: Localidade associada a um endereço" : $mensagem;
            return ["error" => true, "message" => $message];
        }
    }

}
