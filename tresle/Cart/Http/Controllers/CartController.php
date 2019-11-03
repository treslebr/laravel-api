<?php


namespace Tresle\Cart\Http\Controllers;


use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Tresle\Cart\Model\Cart;
use Tresle\Cart\Model\CartQuery;
use \Tresle\Cart\Http\Requests\CartRequest;
use \Illuminate\Http\Request;

class CartController extends Controller
{

    /**
     * @param CartRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|CartQuery
     */
    public function store(CartRequest $request)
    {
        try {
            $user = Auth::user();
            $cart = new CartQuery();
            $cart->insert($request, $user->id);
            return response(["errors" => false, "message" => "Produto adicionado com sucesso."], 200);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível inserir item no carrinho."], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $cart = new CartQuery();
            $items = $cart->getCartItemsByCustomerId($user->id);
            return $items;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Documento não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $cart = Cart::where('customer_id', $user->id)->findOrFail((int)$id);
            $cart->delete();
            return ["error" => false, "message" => "Item excluído com sucesso."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Item do carrinho não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param CartRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function update(CartRequest $request, $id)
    {
        try {
            $user = Auth::user();
            $cart = new CartQuery();
            $updated = $cart->updatePatch($request, $user->id, $id);
            return $updated;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Item do carrinho não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

}
