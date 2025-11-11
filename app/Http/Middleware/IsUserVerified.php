<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Session;
use Closure;

class IsUserVerified
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
        if(auth()->guard('client')->user()->isEmailVerifed == '0')
        {
            return redirect()->route('client.userEmailVerifyForm');
        }
        elseif(auth()->guard('client')->user()->isPhoneNumberVerified == '0')
        {
            return redirect()->route('client.userPhoneNumberVerifyForm');
        }elseif(auth()->guard('client')->user()->status === 'block' || auth()->guard('client')->user()->status === 'inactive'){
            Auth::guard('client')->logout();
            Session::flash('message','Your account is deactivated. using contact us please contact our administrator.');
            Session::flash('alert-class','alert-danger');  
            return redirect('/user/login');
        }

        // if (auth()->guard('client')->user()->swift_user_status === 0) {
            
        //     return redirect()->route('verification.notice');
        // }

        
        return $next($request);
    }
}
