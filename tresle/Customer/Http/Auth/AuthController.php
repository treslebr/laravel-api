<?php
namespace Tresle\Customer\Http\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Tresle\Customer\Model\Customer\Customer;

class AuthController extends \App\Http\Controllers\Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:customer',
            'telephone' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);
        $customer = new Customer([
            'name'      => $request->name,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'cellphone' => $request->cellphone,
            'password'  => bcrypt($request->password)
        ]);
        $customer->save();
        return response()->json([
            'message' => 'Successfully created customer!'
        ], 201);
    }
}
