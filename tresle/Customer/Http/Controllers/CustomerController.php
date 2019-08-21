<?php


namespace Tresle\Customer\Http\Controllers;


use Carbon\Carbon;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        return parent::store($request);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getCustomerLogged(Request $request)
    {
        return Customer::with('addresses.shipping')
            ->find(Auth::id());
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function show(Request $request, $id)
    {
        try {
            $customer = Customer::with('addresses.shipping')->findOrFail((int)$id);
            return ["error" => false, "message" => "", "data" => $customer];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Cliente não encontrado"];
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Customer[]
     */
    public function index()
    {
        return Customer::with('addresses.shipping')->get();
    }
}
