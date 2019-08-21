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
     * @param \Tresle\Order\Http\Requests\OrderRequest $request
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

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        try {
            $order = Order::with("items.additionals")
                ->findOrFail((int)$id);

            return [
                "error" => false,
                "message" => "",
                "data" => $order
            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Pedido não encontrado."];
        }
    }

    /**
 * @return array
 */
    public function index()
    {
        try {
            $order = Order::with("items.additionals")
                ->get();
            return [
                "error" => false,
                "message" => "",
                "data" => $order
            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Pedido não encontrado."];
        }
    }

    /**
     * @return array
     */
    public function getOrderCustomerLogged()
    {
        try {
            $user = Auth::user();
            $order = Order::with("items.additionals")
                ->where("customer_id", $user->id)
                ->get();
            return [
                "error" => false,
                "message" => "",
                "data" => $order
            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Pedido não encontrado."];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return array
     */
    public function getOrderCustomerLoggedById(\Illuminate\Http\Request $request, $id)
    {
        try {
            $user = Auth::user();
            $order = Order::with("items.additionals")
                ->where("customer_id", $user->id)
                ->firstOrFail($id);
            return [
                "error" => false,
                "message" => "",
                "data" => $order
            ];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Pedido não encontrado."];
        }
    }


}
