<?php

namespace Tresle\Product\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tresle\Product\Model\Product\Image;
use Tresle\Product\Model\Product\Product;

class ImageController extends Controller
{

    /**
     * @param \Tresle\Product\Http\Requests\Product\ImageRequest $request
     * @param $idProduct
     * @return array
     */
    public function store(\Tresle\Product\Http\Requests\Product\ImageRequest $request, $idProduct)
    {
        try {
            $product = Product::findOrFail((int)$idProduct);
            $imagem = new Image();

            $file = $request->file('src');
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().'.'.$extension;
            $file->move('uploads/products/', $filename);

            $imagem->src        = $file;
            $imagem->product_id = $idProduct;

            $imagem->save();
            return ["error" => false, "message" => "", "data" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Produto não encontrado"];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o produto";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Categoria não encontrada" : $mensagem;
            return ["error" => true, "message" => $e->getMessage()];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function destroy(\Illuminate\Http\Request $request, $idProduct, $idImage)
    {
        try {
            $image = Image::findOrFail((int)$idImage);
            $image->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Produto não encontrado"];
        }
    }
}
