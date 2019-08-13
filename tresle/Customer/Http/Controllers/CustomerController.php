<?php


namespace Tresle\Customer\Http\Controllers;


use Carbon\Carbon;
use phpDocumentor\Reflection\Types\Parent_;
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

}
