<?php


namespace Tresle\Order\Http\Controllers;


use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Tresle\Cart\Model\CartQuery;
use Tresle\Order\Model\Order;
use Tresle\Order\Model\OrderQuery;
use \Tresle\Order\Http\Requests\OrderRequest;

class OrderController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(\Illuminate\Http\Request $request)
    {
        try {
            $user = Auth::user();
            $order = new OrderQuery();
            $newOrder = $order->insert($user->id, new CartQuery(), (int)$request->input("addressId"));
            return $newOrder;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível realizar o pedido."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param OrderRequest $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(OrderRequest $request, $id)
    {
        try {
            $order = Order::findOrFail((int)$id);
            $data = $request->all();
            $order->update($data);
            return ["errors" => false, "message" => "Status atualizado."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Pedido não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response|Order|Order[]
     */
    public function show($id)
    {
        try {
            return Order::with("items.additionals")
                ->findOrFail((int)$id);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Pedido não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }

    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|Order[]
     */
    public function index()
    {
        try {
            return Order::with("items.additionals")
                ->get();
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|Order[]
     */
    public function getOrderCustomerLogged()
    {
        try {
            $user = Auth::user();
            return $order = Order::with("items.additionals")
                ->where("customer_id", $user->id)
                ->get();
        }catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Pedido não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response|Order|Order[]|null
     */
    public function getOrderCustomerLoggedById(\Illuminate\Http\Request $request, $id)
    {
        try {
            $user = Auth::user();
            return $order = Order::with("items.additionals")
                ->where("customer_id", $user->id)
                ->findOrFail($id);
        }catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Pedido não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }


}
