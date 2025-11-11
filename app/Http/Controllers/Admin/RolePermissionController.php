<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\AdminModule;
use App\Models\Permission;
use App\Models\AdminModulePermission;
use App\Models\AdminRoleModulePermission;
use App\Helpers\Common;
class RolePermissionController extends Controller
{
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display list roles page
    ***/
    public function index()
    {
        $role = new Role;
        return view('admin.roles.index',compact('role'));
    }

   /***
    *   Developed by: Radhika Savaliya
    *   Description: Validate create role request
    ***/
    public function validator($request)
    {
        return $this->validate($request, [
         'name' => 'required|max:50',
      ]);
    }


    /***
    *   Developed by: Radhika Savaliya
    *   Description: Get role list in datatable format to display in list page
    ***/
    public function roleList()
    {
      
        $role = Role::get();

        return datatables()->of($role)
          ->editColumn('status', function ($role) {
              $inactive_url = route('admin.role.inactive', $role->id);
              $active_url = route('admin.role.active', $role->id);
              if($role->status=="active")
              {
                  // return '<a class="active-status" href="'.$inactive_url.'" title="Click here to deactivate" >Active</a>';
                  return '<a class="active-status" href="#">Active</a>';
              }
              else
              {
                  // return '<a class="inactive-status" href="'.$active_url.'" title="Click here to activate" >Inactive</a>';
                  return '<a class="inactive-status" href="#">Inactive</a>';
              }
          })
         
          ->escapeColumns([])
          ->addColumn('action', function ($role) {
              $edit_link = "";
              $delete_link = "";
              $permission_link = "";

              $delete_url = route('admin.role.destroy', $role->id);
              $permission_url = route('admin.role.edit_role_permissions', $role->id);

                if(Common::hasPermission(config('settings.admin_modules.roles_permissions'),config('settings.permissions.edit'))){
                    $edit_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" onClick="editRole('.$role->id.',\''.$role->name.'\')">
                              <span class="edit-icon"></span></a> ';
                    $permission_link = '<a class="btn btn-danger btn-action-icon action-edit edit-btn-clr" title="Set permissions" href="'.$permission_url.'" >
                              <span class="permission-icon"></span></a>';
                }
                if(Common::hasPermission(config('settings.admin_modules.roles_permissions'),config('settings.permissions.delete'))){
                    $delete_link = '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
                                <span class="delete-icon"></span></a>';
                }
                return $edit_link.''.$permission_link.''.$delete_link;
             
          })
          ->make(true);
    }

   
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Save role in database
    ***/
    public function storeRole(Request $request)
    {
        //store  here
        $input = $request->except(['_token']);
        if ($this->validator($request)) {
    
            $role = Role::create($input);

            Common::logActivity($role , Auth::guard('admin')->user(), $role->toArray() ,"New role created.");

            $notification = array(
              'message' =>  __('messages.roleAddSuccess'),
              'alert-type' => 'success'
            );

            return back()->with($notification);
        }
    }

    
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Save updated role details in database
    ***/
    public function roleUpdate(Request $request)
    {
        if ($this->validator($request)) {
            $input = $request->except(['_token']);
            $id = $input['roleId'];

            $role = Role::findOrFail($id);
            
            $role->update($input);

            Common::logActivity($role , Auth::guard('admin')->user(), $role->toArray() ,"Role updated.");

            $notification = array(
                'message' => __('messages.roleUpdateSuccess'),
                'alert-type' => 'success'
              );

            return back()->with($notification);
        }
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Delete role
    ***/
    public function roleDestroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        Common::logActivity($role , Auth::guard('admin')->user(), $role->toArray() ,"Role deleted.");

        $notification = array(
          'message' =>  __('messages.roleDeleteSuccess'),
          'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Deactivate role
    ***/
    public function roleInactive($id)
    {
        $role = Role::findOrFail($id);
        $role->status ='inactive';
        $role->save();

        Common::logActivity($role , Auth::guard('admin')->user(), $role->toArray() ,"Role status updated to inactive.");

        $notification = array(
          'message' =>  __('messages.roleDeactivateSuccess'),
          'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Activate role
    ***/
    public function roleActive($id)
    {
        $role = Role::findOrFail($id);
        $role->status ='active';
        $role->save();

        Common::logActivity($role , Auth::guard('admin')->user(), $role->toArray() ,"Role status updated to inactive.");

        $notification = array(
          'message' =>  __('messages.roleActivateSuccess'),
          'alert-type' => 'success'
        );

        return back()->with($notification);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Edit permissions for role by id
    ***/
    public function editRolePermissions($id)
    {
      
        $role = Role::find($id);
        $permissions = Permission::get();
        $modules = AdminModule::with(['module_permissions'])->orderBy('name')->get();
        
        foreach($modules as $module)
        {
          $modulePermissions[$module->id] = $module->module_permissions->pluck('permission_id')->toArray();


          $modulePermissionIds[$module->id] = $module->module_permissions->pluck('id')->toArray();
          

          // $rolePermissions[$module->id] = AdminRoleModulePermission::with(['module_permission'])->where('role_id', $id)->whereIn('module_permission_id', $modulePermissions)->get();
          // print_r($rolePermissions[$module->id]);
          // die;

          //$roleModulePermissions = AdminRoleModulePermission::with(['module_permission'])->where('role_id', $id)->pluck('module_permission_id')->toArray();
        }

        $rolePermissions = AdminRoleModulePermission::where('role_id', $id)->pluck('module_permission_id')->toArray();
        
        return view('admin.roles.edit-permissions')->with(compact('role','permissions','modules','rolePermissions','modulePermissionIds','modulePermissions'));
    }
    
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Edit permissions for role by id
    ***/
    public function updateRolePermissions(Request $request, $id)
    {
        AdminRoleModulePermission::where('role_id', $id)->delete();
       
        if(isset($request->module_permissions))
        {
            foreach($request->module_permissions as $permissionId)
            {
                $permissionData['role_id'] = $id;
                $permissionData['module_permission_id'] = $permissionId;
                AdminRoleModulePermission::create($permissionData);
            }
        }

        $notification = array(
          'message' =>  __('messages.rolePermissionUpdateSuccess'),
          'alert-type' => 'success'
        );

        return back()->with($notification);
    }
   
}
