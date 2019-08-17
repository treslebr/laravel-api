<?php


namespace Tresle\Cart\Model;


use Tresle\Cart\Http\Requests\CartRequest;
use Tresle\Product\Model\Product\Product;

class CartQuery
{
    /**
     * @param CartRequest $request
     * @param Cart $cart
     * @param $customerId
     */
    public function insert(CartRequest $request, Cart $cart, $customerId){
        $additionalsId = $request->input("additionals_id");
        $productId     = $request->input("product_id");
        $product       = Product::find($productId);

        if(!is_array($additionalsId)) $additionalsId = (array)$additionalsId;

        $additionalsChecked = [];
        foreach ($additionalsId as $id){
            $exist = $product->additionals()->where('product_additional.id', $id)->first();
            if($exist){
                $additionalsChecked[] = $id;
            }
        }

        $cart->create(array(
            "customer_id"    => $customerId,
            "qty"            => $request->input("qty"),
            "obs"            => $request->input("obs"),
            "product_id"     => $request->input("product_id"),
            "additionals_id" => json_encode($additionalsChecked),
        ));
    }
}
