<?php


namespace Tresle\Order\Model;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tresle\Cart\Model\Cart;
use Tresle\Cart\Model\CartQuery;
use Tresle\Customer\Model\Address\Address;

class OrderQuery
{
    /**
     * @param $customerId
     * @param CartQuery $cart
     * @param $addressId
     * @return array
     */
    public function insert($customerId, CartQuery $cart, $addressId)
    {
        try {
            $items = $cart->getCartItemsByCustomerId($customerId);

            if($items->isEmpty()){
                return response(["errors" => true, "message" => "Carrinho vazio."], 404);
            }

            $customerAdress = Address::with("shipping")
                ->where("id", $addressId)
                ->where("customer_id", $customerId)
                ->firstOrFail();

            \DB::beginTransaction(); //marcador para iniciar transações

            $order = new Order();
            $order->customer_id = $customerId;
            $order->postcode = $customerAdress->postcode;
            $order->country = $customerAdress->country;
            $order->state = $customerAdress->state;
            $order->city = $customerAdress->city;
            $order->street_1 = $customerAdress->street_1;
            $order->street_2 = $customerAdress->street_2;
            $order->street_3 = $customerAdress->street_3;
            $order->shipping_location = $customerAdress->shipping->location;
            $order->shipping_price = $customerAdress->shipping->price;
            $order->save();

            foreach ($items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_name = $item->product->name;
                $orderItem->product_price = $item->product->price;
                $orderItem->obs = $item->obs;
                $orderItem->qty = $item->qty;
                $orderItem->save();
                foreach ($item->additionals as $additional) {
                    $additionalItems = new OrderItemAdditional();
                    $additionalItems->order_item_id = $orderItem->id;
                    $additionalItems->additional_name = $additional->name;
                    $additionalItems->additional_price = $additional->price;
                    $additionalItems->save();
                }

            }

            Cart::where("customer_id", $customerId)->delete();
            \DB::commit(); //validar as transações
            return response(["errors" => false, "message" => "Pedido realizado com sucesso."], 201);
        }catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Endereço não encontrado."], 422);
        }catch (\Exception $e) {
            \DB::rollback(); //reverter tudo, caso tenha acontecido algum erro.
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }catch (\Illuminate\Database\QueryException $e) {
            \DB::rollback(); //reverter tudo, caso tenha acontecido algum erro.
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }

    }

}
