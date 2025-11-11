<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\AdminPasswordReset;
use App\Helpers\Common;

class ForgotPasswordController extends Controller
{
   
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }


    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display forgot password page
    ***/

    public function requestPassword()
    {

        return view('admin.auth.request-password');
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Send email to user on forgot password submit
    ***/
    public function requestPasswordSubmit(Request $request)
    {
        $this->validate($request, [
             'email' => 'required'
        ]);
        try
        {
            $this->sendResetLinkEmail($request);  

            $admin = Admin::where('email','=',$request->email)->first();

            return redirect(route('admin.password.request'))->with('success',__('messages.passwordRequestSuccess'));
        }
        catch(\Exception $e)
        {
            return redirect(route('admin.password.request'))->withErrors([__('messages.serverError')]);
        }
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Use admin table for reset password requests
    ***/
    public function broker()
    {
        return Password::broker('admins');
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display reset password page
    ***/
    public function resetPassword($token, Request $request)
    {
        $ifExist = AdminPasswordReset::where('email','=', $request->email)->first();


        
        if(!$ifExist)
        {
            return redirect(route('admin.password.request'))->withErrors(['error' => __('messages.invalidRequest')]);
        }
        else
        {
            if(!Hash::check($token, $ifExist->token)){
                return redirect(route('admin.password.request'))->withErrors(['error' => __('messages.invalidRequest')]);
            }
        }
        $data['token'] = $token;
        return view('admin.auth.reset-password',$data);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Validate data and update password on reset password form submit
    ***/
    public function resetPasswordSubmit(Request $request)
    {
        
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password'  => 'required|min:6',
            'password_confirmation'  => 'required|min:6|same:password'
        ]);

        try
        {
            
            
            $ifExist = AdminPasswordReset::where('email','=', $request->email)->first();

            if(!$ifExist)
            {
                return redirect(route('admin.password.request'))->withErrors(['error' => __('messages.invalidRequest')]);
            }
            else
            {
                if(!Hash::check($request->token, $ifExist->token)){
                    return redirect(route('admin.password.request'))->withErrors(['error' => __('messages.invalidRequest')]);
                }
            }

            $adminId = Admin::where('email','=',$request->email)->first()->id;
            $adminUser = Admin::find($adminId);
            $adminUser->password =  Hash::make($request->password);
            $adminUser->save();


            /*** Send reset password notification to user by email ***/
            $adminUser->sendPasswordResetSuccessNotification($adminUser);

            

            return redirect(route('admin.loginindex'))->with(['success' => __('messages.passwordResetSuccess')]);
           
        }
        catch(\Exception $e)
        {
            echo $e->getMessage(); die;
            return redirect(route('admin.password.request'))->withErrors([__('messages.serverError')]);
        }

      
    }
}
