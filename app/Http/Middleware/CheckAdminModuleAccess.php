<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Common;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckAdminModuleAccess
{
    /***
    *   Developed by: Khushbu Jajal
    *   Description: Allow user to access page only if his trial period is active or he has purchased authentic package
    ***/
    public function handle($request, Closure $next)
    {
        
        if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role_id != 0 && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
        {
            
            $url = $request->url();
            // dd($url,Str::contains($url, 'currency-settings'),$moduleId = config('settings.admin_modules.currency_settings'));
            $dashboardRoute = Common::getDashboardLink();

          //  dd($url);
            /*** Check role vise module access ***/
            if(Str::contains($url, 'admins'))
            {
                $moduleId = config('settings.admin_modules.admin_users');             
            }
            else if(Str::contains($url, 'add-money-to-nium'))
            {
                $moduleId = config('settings.admin_modules.add_money_in_nium_account');     
            }
            else if(Str::contains($url, 'cms_settings'))
            {
                $moduleId = config('settings.admin_modules.cms_settings');  
            }
            else if(Str::contains($url, 'recieved-transactions'))
            {
                $moduleId = config('settings.admin_modules.transactions_recieved_money');     
            }
            else if(Str::contains($url, 'transactions'))
            {
                $moduleId = config('settings.admin_modules.transactions');  
            }
            else if(Str::contains($url, 'tickets'))
            {
                $moduleId = config('settings.admin_modules.contact');  
            }
            else if(Str::contains($url, 'support-ticket-category'))
            {
                $moduleId = config('settings.admin_modules.support_ticket_category');  
            }  
            else if(Str::contains($url, 'support-ticket-templates'))
            {
                $moduleId = config('settings.admin_modules.support_ticket_category');  
            } 
            else if(Str::contains($url, 'clients'))
            {
                $moduleId = config('settings.admin_modules.clients');  
            }
            else if(Str::contains($url, 'beneficiars'))
            {
                $moduleId = config('settings.admin_modules.beneficiars');  
            }
            else if(Str::contains($url, 'referral-partners'))
            {
                $moduleId = config('settings.admin_modules.refferal_partners');  
            }
            else if(Str::contains($url, 'roles'))
            {
                $moduleId = config('settings.admin_modules.roles_permissions');  
            }
            else if(Str::contains($url, 'email-templates'))
            {
                $moduleId = config('settings.admin_modules.email_templates');  
            }
            else if(Str::contains($url, 'purpose-code'))
            {
                $moduleId = config('settings.admin_modules.purpose_codes');  
            }
            else if(Str::contains($url, 'activity-log'))
            {
                $moduleId = config('settings.admin_modules.activity_log');  
            }
            else if(Str::contains($url, 'contact'))
            {
                $moduleId = config('settings.admin_modules.contact');  
            }
            else if(Str::contains($url, 'direct-entry-dishonour'))
            {
                $moduleId = config('settings.admin_modules.direct_entry_dishonours');  
            }
            else if(Str::contains($url, 'npp-returns'))
            {
                $moduleId = config('settings.admin_modules.npp_returns');  
            }
            else if(Str::contains($url, 'currency-settings'))
            {
                $moduleId = config('settings.admin_modules.currency_settings');  
            }
            else if(Str::contains($url, 'settings'))
            {
                $moduleId = config('settings.admin_modules.settings');  
            }    
            else
            {
                return $next($request);
            }
         
            if(Str::contains($url, 'create') || Str::contains($url, 'save') || Str::contains($url, 'store'))
            {
                $permissionId = config('settings.permissions.create');
            }
            else if(Str::contains($url, 'edit') || Str::contains($url, 'update') || Str::contains($url, 'active') || Str::contains($url, 'inactive')  || Str::contains($url, 'block'))
            {
                $permissionId = config('settings.permissions.edit');  
            }
            else if(Str::contains($url, 'delete') || Str::contains($url, 'destroy'))
            {
                $permissionId = config('settings.permissions.delete');  
            }
            else if(Str::contains($url, 'verify') || Str::contains($url,'verification'))
            {               
                $permissionId = config('settings.permissions.verify');
            }
            else if(Str::contains($url, 'approve') || Str::contains($url, 'reject') || Str::contains($url, 'deactivate-spam-user') || Str::contains($url, 'activate-spam-user') || Str::contains($url, 'mark-report-spam') )
            {
                $permissionId = config('settings.permissions.approve');  
            }                      
            else
            {                
                $permissionId = config('settings.permissions.view');  
            }
          
            
            $accessible = Common::hasPermission($moduleId,$permissionId);
            
            if(!$accessible)
            {
                return redirect($dashboardRoute)->withErrors([__('messages.nopermission')]);
            }
            
            
        }
        
        return $next($request);
        
    }
}
