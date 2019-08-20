<?php


namespace Tresle\Order\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Tresle\Cart\Model\CartQuery;
use Tresle\Order\Model\OrderQuery;

class OrderController extends Controller
{

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
}
