<?php

namespace Tresle\Product\Http\Controllers\Additional;

use App\Http\Controllers\Controller;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tresle\Product\Model\Additional\Additional;
use \Tresle\Product\Http\Requests\Additional\AdditionalRequest;

class AdditionalController extends Controller
{
    const NAO_ENCONTRADO = "Produto adicional não encontrado";

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|Additional[]
     */
    public function index()
    {
        try {
            return Additional::with(["category", "products"])->get();
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param AdditionalRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(AdditionalRequest $request)
    {
        try {
            $data = $request->all();
            $additional = Additional::create($data);
            return $additional;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar o produto adicional."], 404);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response|Additional|Additional[]
     */
    public function show($id)
    {
        try {
            return Additional::with(["category", "products"])->findOrFail($id);;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param AdditionalRequest $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(AdditionalRequest $request, $id)
    {
        try {
            $additional = Additional::findOrFail((int)$id);
            $data = $request->all();
            $additional->update($data);
            return ["error" => false, "message" => "Produto atualizado."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        }catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $additional = Additional::findOrFail((int)$id);
            $additional->delete();
            return ["error" => false, "message" => "Produto excluído."];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => self::NAO_ENCONTRADO], 404);
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir a categoria";
            $message = strpos($e->getMessage(), "delete") ? "{$mensagem}: Esse produto possui alguma associação." : $mensagem;
            return response(["errors" => true, "message" => $message], 422);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param string $name
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function search(string $name)
    {
        try {
            $result = Additional::where("name", "like", "%$name%")
                ->orderBy('name', 'ASC')
                ->with("category")
                ->where("status", true)->get();

            return $result;
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }

    }
}
