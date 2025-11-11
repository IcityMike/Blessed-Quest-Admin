<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
// use Browser;
use Route;

use App\Models\Admin;
use App\Helpers\Common;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    protected $guard = 'admin';
    protected $redirectTo = '/administrator/dashboard';
    protected $loginPath = '/administrator';

    public function __construct()
    {
        $this->redirectTo = '/administrator/dashboard';
    }
  
    /***
    *   Developed by: Radhika Savaliya
    *   Description: logout user from session and redirect to login page
    ***/

    public function logout()
    {
        Auth::guard('admin')->logout();
        \Session::flush();
        return redirect()->route('admin.loginindex');
    }


    /***
    *   Developed by: Radhika Savaliya
    *   Description: Validate login request, If invalid, throw error
    *   If valid, login user to session and redirect to dashboard
    ***/
    public function login(Request $request)
    {
        $this->validate($request, [
         'email' => 'required',
         'password' => 'required',
        ]);
        $admin = Admin::where('email', $request->email)->first();
        if (!$admin) {
            //return redirect($this->loginPath)->with('error', 'User does not exist');
            $error = ValidationException::withMessages([
             // 'email' => "Credentials does not match with our records",
                'email' => "The email address you entered does not match any account. Please try again or create a new account.",
          ]);
            throw $error;
        }

        if($admin->status=="block")
        {
            return redirect($this->loginPath)
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Account is deactivated']);
        }
        if($admin->status=="inactive")
        {
            return redirect($this->loginPath)
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Account is deactivated']);
        }
        
        if (Hash::check($request->password, $admin->password)) {

            Auth::guard('admin')->login($admin,isset($request->remember));
   
             /** Log activity **/
             if(isset($request->remember)&&!empty($request->remember)){
                setcookie("email",$request->email,time()+3600);
                setcookie("password",$request->password,time()+3600);
             }else{
                setcookie("email","");
                setcookie("password","");
             }

             $details = 
             array(
                 "ip_address" => $request->ip(),
                 "time" => date("Y-m-d H:i:s"),
                // "browser" => Browser::browserName(),
                 "user_agent" => $request->header('User-Agent')

             );
            Common::logActivity($admin,$admin ,$details,"Admin logged in.");

             $notification = array(
                'message' => __('Login successfully.!'),
                'alert-type' => 'success'
              );

            return redirect()->route('admin.dashboard')->with($notification);
        }

        return redirect($this->loginPath)
         ->withInput($request->only('email', 'remember'))
        // ->withErrors(['email' => 'Incorrect email address or password']);
         ->withErrors(['password' => 'Incorrect password. Please try again.']);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display login page
    ***/
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
       
        return view('admin.auth.login');
    }

    
}
