<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
class Authenticate extends Middleware
{
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            
            if (request()->route()->getPrefix() == "/administrator") {
                return route('admin.loginindex');
            }
            if (request()->route()->getPrefix() == "/user") {
                return route('client.loginindex');
            }
            if (request()->route()->getPrefix() == "/refferal-partner") {
                return route('refferal.index');
            }
            if (request()->route()->getPrefix() == "api") {
                throw new HttpResponseException(response()->json(['status'=>false,'message' => 'Unauthenticated.'], 401));
            }
        }
    }
}
