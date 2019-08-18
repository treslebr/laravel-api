<?php


namespace Tresle\Cart\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Tresle\Cart\Model\Cart;
use Tresle\Cart\Model\CartQuery;

class CartController extends Controller
{

    /**
     * @param \Tresle\Cart\Http\Requests\CartRequest $request
     * @return array
     */
    public function store(\Tresle\Cart\Http\Requests\CartRequest $request)
    {
        try {
            $user = Auth::user();
            $cart = new CartQuery();
            $cart->insert($request, $user->id);
            return ["error" => false, "message" => "", "data" => $cart];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Erro ao inserir item no carrinho"];
        }catch (\Illuminate\Database\QueryException $e) {
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Product[]
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $cart = new CartQuery();
            $items = $cart->getCartItemsByCustomerId($user->id);
            return ["error" => false, "message" => "", "data" => $items];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Erro ao inserir item no carrinho"];
        }catch (\Illuminate\Database\QueryException $e) {
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return array
     */
    public function destroy(\Illuminate\Http\Request $request, $id)
    {
        try {
            $cart = Cart::findOrFail((int)$id);
            $cart->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Item do carrinho não encontrado."];
        }
    }

}
