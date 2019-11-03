<?php

namespace Tresle\User\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class AuthCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->is_admin == 0){
            return $next($request);
        }
        return response()->json(
            [
                'message' => 'Unauthorized',
                "errors" => ""
            ],
            401
        );
    }

}
