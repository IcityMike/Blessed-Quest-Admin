<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
class VerifySwiftIdMiddleware
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
        // dd(auth()->guard('client')->user());
        if (empty(auth()->guard('client')->user()->swift_user_status) || auth()->guard('client')->user()->swift_user_status === 0 || auth()->guard('client')->user()->swift_user_status === 2) {
            // dd('fdfs');
            return redirect()->route('client.dashboard');
        }

        return $next($request);
    }
}
