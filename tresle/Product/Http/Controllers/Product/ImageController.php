<?php

namespace Tresle\Product\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tresle\Product\Model\Product\Image;
use Tresle\Product\Model\Product\Product;
use \Tresle\Product\Http\Requests\Product\ImageRequest;

class ImageController extends Controller
{
    const NAO_ENCONTRADO = "Imagem não encontrada.";

    /**
     * @param \Tresle\Product\Http\Requests\Product\ImageRequest $request
     * @param $idProduct
     * @return array
     */
    public function store(ImageRequest $request, $idProduct)
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
            return response(["errors" => false, "id" => $imagem->id, "message" => "Imagem cadastrada com sucesso."], 201);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Produto não encontrado."], 404);
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao cadastrar o produto";
            $message = strpos($e->getMessage(), "a foreign key constraint fails") ? "{$mensagem}: Categoria não encontrada" : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
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
            return ["error" => false, "message" => "Imagem excluída"];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Produto não encontrado."], 404);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }
}
