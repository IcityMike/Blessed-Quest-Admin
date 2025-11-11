<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use App\Models\Admin;
use App\Models\Role;
use App\Helpers\Common;
use Validator;
use DB;

class AdminController extends Controller
{
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display list admin users page
    ***/
    public function index()
    {
        if(Common::hasPermission(config('settings.admin_modules.admin_users'),config('settings.permissions.view'))){
            return view('admin.admins.index');

        }else{
            return redirect()->back();
        }
    }

   /***
    *   Developed by: Radhika Savaliya
    *   Description: Validate create admin request
    ***/
    public function validator($request)
    {
        return $this->validate($request, [
         'first_name' => 'required|string|max:255',
         'last_name' => 'required|string|max:255',
         'email' => 'required|email|max:255|unique:admins,deleted_at,NULL',
       //  'password' => 'required|min:6|confirmed',
         'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
      ],
        [
            'password.rules' => 'You have to choose the file!'
        ]);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Validate update admin request
    ***/
    public function updateValidator($request)
    {   
        if($request->password){
            return  $this->validate($request, [
             'first_name' => 'required|string|max:255',
             'last_name' => 'required|string|max:255',
            'role_id' => 'required',
            'phone' => 'required|string|min:10',
             //'password' => 'confirmed',
            'password' => 'required|string|min:8|max:16|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
           ]
           ,[
                'password.regex' => 'Password must be 8-16 characters and include uppercase letters, lowercase letters, numbers, and special characters.',
                 'role_id.required' => 'The role field is required.'
            ]);

        }else{

            return  $this->validate($request, [
                 'first_name' => 'required|string|max:255',
                 'last_name' => 'required|string|max:255',
                  'role_id' => 'required',
                  'phone' => 'required|string|min:10',
                 //'password' => 'confirmed',
              //  'password' => 'required|string|min:8|max:16|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            ]);
        }
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Get Admin list in datatable format to display in list page
    ***/
    public function adminList()
    {
        $admin = '';
        $admin = Admin::select('id',DB::raw("CONCAT(`first_name`,' ',`last_name`) AS name"),'role_id','email','phone','status','type')
          ->where('id', '!=', Auth::guard('admin')->user()->id)
          ->orderBy('id','desc')
          ->get();

        return datatables()->of($admin)
          ->editColumn('name', function ($admin) {
             
              if($admin->name)
              {//ucfirst
                  return  ucfirst($admin->name);
              }
              else
              {
                  return '';
              }
          })
          ->editColumn('status', function ($admin) {
              $inactive_url = route('admin.block', $admin->id);
              $active_url = route('admin.active', $admin->id);
              if($admin->status=="active")
              {
                  return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to deactivate" >Active</a>';
              }
              else
              {
                  return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Inactive</a>';
              }
          })
          ->editColumn('type', function ($admin) {
            $type = "";
            if($admin->type == "sub"){
                $type = "Team Member";
            }else{
                $type = "Super Admin";
            }
            // return ($admin->type == "sub" ? "Sub admin" : ($admin->type == "staff"  ? 'Staff member' : 'Admin'));
            return $type;
          })
       
          ->escapeColumns([])
          ->addColumn('action', function ($admin) {
                $edit_link = "";
                $delete_link = "";
                $edit_url = route('admin.edit', $admin->id);
                $delete_url = route('admin.destroy', $admin->id);
                if(Common::hasPermission(config('settings.admin_modules.admin_users'),config('settings.permissions.edit'))){
                    $edit_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
                    <span class="edit-icon"></span>
                  </a>';
                }
                if(Common::hasPermission(config('settings.admin_modules.admin_users'),config('settings.permissions.delete'))){
                    $delete_link = '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
                    <span class="delete-icon"></span>
                  </a>';
                }
              return $edit_link.''.$delete_link;
             
          })
          ->make(true);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display create admin page
    ***/

    public function createAdmin()
    {
        $roles = Role::orderBy('name')->where('status','active')->get();
        if(Auth::user()->type == "super" && Common::hasPermission(config('settings.admin_modules.admin_users'),config('settings.permissions.create'))){
            $admin = new Admin;
            return view('admin.admins.create')->with(compact('admin','roles'));
        }else{
            return redirect()->back();
            // abort(409);
        }
        
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Save admin user in database
    ***/
    public function storeAdmin(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|min:10',
           // 'email' => 'required|email:rfc,dns|unique:admins,email,NULL,id,deleted_at,NULL',
            'email' => 'required|email:rfc,dns|unique:admins,email,NULL,id,deleted_at,NULL',
            'role_id' => 'required',
            // 'type' => 'required'
           // 'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],

            'password' => 'required|string|min:8|max:16|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            ],
            [
                'password.regex' => 'Password must be 8-16 characters and include uppercase letters, lowercase letters, numbers, and special characters.',
                'role_id.required' => 'The role field is required.'
            ]);
            
           // dd($validation);
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        //store  here
        $input = $request->except(['_token']);
        // if ($this->validator($request)) {

            // 'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            $plainPassword = $input['password'];
            $input['password'] = bcrypt($input['password']);
            $input['status'] = 'active';
            $input['type'] = 'sub';
            // $input['type'] = $request->type;
            $admin = Admin::create($input);
            $admin->plainPassword =$plainPassword;
            
            $admin->sendWelcomeEmail($admin);

            /** Log activity **/
          //  Common::logActivity($admin , Auth::guard('admin')->user()->id , $admin->toArray() ,"New admin created.");

            $notification = array(
              'message' =>  __('messages.adminAddSuccess'),
              'alert-type' => 'success'
            );

            return redirect()->route('admin.index')->with($notification);
       // }
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display edit admin page
    ***/
    public function adminEdit($id)
    {
        $roles = Role::orderBy('name')->where('status','active')->get();
        if(Auth::user()->type == "super" && Common::hasPermission(config('settings.admin_modules.admin_users'),config('settings.permissions.edit'))){
            $admin = Admin::findOrFail($id);
            return view('admin.admins.create')->with(compact('admin','roles'));
        }else{
            return redirect()->back();
            // abort(409);
        }

        
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Save updated admin details in database
    ***/
    public function adminUpdate(Request $request, $id)
    {
        if ($this->updateValidator($request)) {
            $input = $request->except(['_token','email']);
            $admin = Admin::findOrFail($id);
            $input['password'] = (isset($input['password']) && !empty($input['password'])) ? bcrypt($input['password']) : $admin->password;
            // $input['type'] = $request->type;
            $input['type'] = 'sub';
            // $input['role_id'] = 1;
            $admin->update($input);

             /** Log activity **/
            Common::logActivity($admin , Auth::guard('admin')->user()->id , $admin->toArray() ,"Admin details updated.");

            $notification = array(
                'message' => __('messages.adminUpdateSuccess'),
                'alert-type' => 'success'
              );

            return redirect()->route('admin.index')->with($notification);
        }
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Delete admin user
    ***/
    public function adminDestroy($id)
    {
        if(Auth::user()->type == "super"){
            $admin = Admin::findOrFail($id);
        
            /** Log activity **/
            Common::logActivity($admin , Auth::guard('admin')->user()->id , $admin->toArray() ,"Admin record deleted.");

            $admin->delete();

            $notification = array(
              'message' =>  __('messages.adminDeleteSuccess'),
              'alert-type' => 'success'
            );

            return redirect()->route('admin.index')->with($notification);
        }else{
            abort(409);
        }
        
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Deactivate admin user
    ***/
    public function adminBlock($id)
    {
        $admin = Admin::findOrFail($id);
        // $admin->status ='block';
        $admin->status ='inactive';
        $admin->save();

        /** Log activity **/
        Common::logActivity($admin , Auth::guard('admin')->user()->id , $admin->toArray() ,"Admin status updated to inactive.");

        $notification = array(
          'message' =>  __('messages.adminDeactivateSuccess'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.index')->with($notification);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Activate Admin user
    ***/
    public function adminActive($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->status ='active';

        /** Log activity **/
        Common::logActivity($admin , Auth::guard('admin')->user()->id , $admin->toArray() ,"Admin status updated to active.");

        $admin->save();

        $notification = array(
          'message' =>  __('messages.adminActivateSuccess'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.index')->with($notification);
    }


   
}
