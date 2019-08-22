<?php


namespace Tresle\Customer\Http\Controllers;


use Carbon\Carbon;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use phpDocumentor\Reflection\Types\Parent_;
use Tresle\Customer\Model\Customer\Customer;
use Tresle\User\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends \Tresle\User\Http\Auth\AuthController
{
    /**
     * @var bool
     */
    protected $isAdmin = false;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            return parent::store($request);;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar o usuário."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response|Customer|Customer[]|null
     */
    public function getCustomerLogged(Request $request)
    {
        try {
            return Customer::with('addresses.shipping')
                ->find(Auth::id());
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\Response|Customer|Customer[]
     */
    public function show(Request $request, $id)
    {
        try {
            return Customer::with('addresses.shipping')->findOrFail((int)$id);;
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Cliente não encontrado."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|Customer[]
     */
    public function index()
    {
        try {
            return Customer::with('addresses.shipping')->get();;
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }
}
