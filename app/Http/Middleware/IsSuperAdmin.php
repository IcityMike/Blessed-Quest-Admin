<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Common;

class IsSuperAdmin
{
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Redirect user to dashboard if he is not personal advice client.
    ***/
    public function handle($request, Closure $next)
    {
        
       
        $dashboardRoute = Common::getDashboardLink();
        
        
        if (auth()->guard('admin')->user()->type == "sub") {
            
            return redirect($dashboardRoute)->withErrors([__('messages.nopermission')]);
        }
            
        return $next($request);
        
    }
}
