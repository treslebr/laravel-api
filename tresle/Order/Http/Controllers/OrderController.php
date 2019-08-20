<?php


namespace Tresle\Order\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Tresle\Cart\Model\CartQuery;
use Tresle\Order\Model\Order;
use Tresle\Order\Model\OrderQuery;

class OrderController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function store(\Illuminate\Http\Request $request)
    {
        try {
            $user = Auth::user();
            $order = new OrderQuery();
            $newOrder = $order->insert($user->id, new CartQuery(), (int)$request->input("addressId"));
            return ["error" => false, "message" => "", "data" => $newOrder];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Erro ao inserir item no carrinho"];
        }catch (\Illuminate\Database\QueryException $e) {
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    /**
     * @param \Tresle\Cart\Http\Requests\CartRequest $request
     * @param $id
     * @return array
     */
    public function update(\Tresle\Order\Http\Requests\OrderRequest $request, $id)
    {
        try {
            $order = Order::findOrFail((int)$id);
            $data = $request->all();
            $order->update($data);
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Pedido não encontrado."];
        }

    }
}
