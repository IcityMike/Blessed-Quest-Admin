<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\CMSTemplate;
use App\Helpers\Common;
use DB;

class CmsController extends Controller
{
    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Display list email templates page
    ***/
    public function index()
    {
       
        return view('admin.CMS.index');
    }

  
    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Validate update email template request
    ***/
    public function updateValidator($request)
    {
        return  $this->validate($request, [
         'name' => 'required|max:255',
         'body' => 'required'
       ]);
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Get email template list in datatable format to display in list page
    ***/
    public function CmsList()
    {
        $cms_templates = '';
        $cms_templates = CMSTemplate::select('id','name','updated_at')->orderBy('id','desc')->get();

        return datatables()->of($cms_templates)
          ->editColumn('updated_at', function ($cms_templates) {
            $date = strtotime($cms_templates->updated_at);
            return ($cms_templates->updated_at) ? '<span style="display:none">'.$date.'</span>' .date("d/m/Y",strtotime($cms_templates->updated_at)) : '-';
          })
          ->addColumn('updated_at_normal', function ($cms_templates) {
             
             return ($cms_templates->updated_at) ? date("d/m/Y",strtotime($cms_templates->updated_at)) : '-';
          })
          ->addColumn('action', function ($cms_templates) {

              if(Common::hasPermission(config('settings.admin_modules.cms_settings'),config('settings.permissions.view'))){

                $edit_url = route('admin.cmsSetting.edit', $cms_templates->id);
                return '<a class="btn action-edit btn-primary btn-action-icon edit-btn-clr" title="Edit" href="'.$edit_url.'">
                <span class="edit-icon"></span>
                </a>';

              }
             
          })
          ->escapeColumns([])
          ->make(true);
    }

   
    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Display edit cms template page
    ***/
    public function CmsEdit($id)
    {
        $cms_templates = CMSTemplate::findOrFail($id);
        return view('admin.CMS.create')->with(compact('cms_templates'));
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Save updated email template details in database
    ***/
    public function CmsUpdate(Request $request, $id)
    {
        if ($this->updateValidator($request)) {
            $input = $request->except(['_token','email']);
            $input['updated_at'] = now();

            $cms_templates = CMSTemplate::findOrFail($id);
            
            $cms_templates->update($input);

            /** Log activity **/
            Common::logActivity($cms_templates , Auth::guard('admin')->user()->id , $cms_templates->toArray() ,"Email template updated.");

            $notification = array(
                'message' => __('messages.emailTemplateUpdateSuccess'),
                'alert-type' => 'success'
              );

            return redirect()->route('admin.cmsSetting.index')->with($notification);
        }
    }

  
}
