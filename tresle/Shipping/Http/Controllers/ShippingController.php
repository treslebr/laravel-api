<?php


namespace Tresle\Shipping\Http\Controllers;


use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Shipping\Model\Shipping;
use \Tresle\Shipping\Http\Requests\Shipping as ShippingRequest;

class ShippingController extends Controller
{

    /**
     * @param ShippingRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(ShippingRequest $request)
    {
        try {
            $data = $request->all();
            return Shipping::create($data);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar o bairro."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param ShippingRequest $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(\Tresle\Shipping\Http\Requests\Shipping $request, $id)
    {
        try {
            $shipping = Shipping::findOrFail((int)$id);
            $data = $request->all();
            $shipping->update($data);
            return ["errors" => false, "message" => "Frete atualizado com sucesso."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Localidade não encontrada."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return Shipping::findOrFail((int)$id);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Localidade não encontrada."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return Shipping::get();
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
            $shipping = Shipping::findOrFail((int)$id);
            $shipping->delete();
            return ["errors" => false, "message" => "Frete excluído com sucesso."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Localidade não encontrada."], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir a localidade";
            $message = strpos($e->getMessage(), "delete") ? "{$mensagem}: Localidade associada a um endereço" : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

}
