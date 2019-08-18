<?php


namespace Tresle\Cart\Model;

use Illuminate\Support\Facades\Log;
use Tresle\Cart\Http\Requests\CartRequest;
use Tresle\Product\Model\Additional\Additional;
use Tresle\Product\Model\Product\Product;

class CartQuery
{
    /**
     * @param CartRequest $request
     * @param $customerId
     */
    public function insert(CartRequest $request, $customerId){
        $additionalsId = $request->input("additionals_id");
        $productId     = $request->input("product_id");
        $product       = Product::find($productId);

        $additionalsChecked = $this->checkAdditionalsId($product, $additionalsId);

        Cart::updateOrCreate(
            [
                'customer_id' => $customerId,
                'product_id'  => (int)$request->input("product_id")
            ] ,
            [
                "qty"            => (int)$request->input("qty"),
                "obs"            => $request->input("obs"),
                "additionals_id" => json_encode($additionalsChecked)
            ]
        );
    }

    /**
     * Verifica se os adicionais realmente pertencem ao produto
     *
     * @param Tresle\Product\Model\Product\Product $product
     * @param array $additionalsId
     * @return array
     */
    private function checkAdditionalsId($product, $additionalsId){
        if(!is_array($additionalsId)) $additionalsId = (array)$additionalsId;
        $additionalsChecked = [];
        foreach ($additionalsId as $id){
            $exist = $product->additionals()->where('product_additional.id', $id)->first();
            if($exist){
                $additionalsChecked[] = $id;
            }
        }
        return $additionalsChecked;
    }

    /**
     * @param CartRequest $request
     * @param $customerId
     * @param $cartId
     * @return mixed
     */
    public function updatePatch(CartRequest $request, $customerId, $cartId){
        $additionalsId = $request->input("additionals_id");
        $cart          = Cart::where("customer_id", $customerId)->find($cartId);
        $product       = Product::find($cart->product_id);

        $additionalsChecked = $this->checkAdditionalsId($product, $additionalsId);


        if($request->input("qty")) $cart->qty = (int)$request->input("qty");
        if($request->input("obs")) $cart->obs = $request->input("obs");
        if($additionalsId) $cart->additionals_id = json_encode($additionalsChecked);
        $cart->save();
        return $cart;
    }
    /**
     * @param $customerId
     * @return mixed
     */
    public function getCartItemsByCustomerId($customerId){
        $items = Cart::where("customer_id", $customerId)
            ->with("product")
            ->get();

        foreach ($items as $item){
            $arrayAdditionalsId = json_decode($item->additionals_id);
            $arrayAdditional = [];
            foreach ($arrayAdditionalsId as $additionalId){
                $arrayAdditional[] = Additional::find($additionalId);
            }
            $item->additionals = $arrayAdditional;
        }
        return $items;
    }
}
