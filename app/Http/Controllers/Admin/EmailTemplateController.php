<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\EmailTemplate;
use App\Helpers\Common;
use DB;

class EmailTemplateController extends Controller
{
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display list email templates page
    ***/
    public function index()
    {
       
        return view('admin.email_templates.index');
    }

  
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Validate update email template request
    ***/
    public function updateValidator($request)
    {
        return  $this->validate($request, [
         'name' => 'required|max:255',
         'subject' => 'required|max:255',
         'body' => 'required'
       ]);
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Get email template list in datatable format to display in list page
    ***/
    public function emailTemplateList()
    {
        $email_templates = '';
        $email_templates = EmailTemplate::select('id','name','updated_at')->orderBy('id','desc')->get();

        return datatables()->of($email_templates)
          ->editColumn('updated_at', function ($email_templates) {
            $date = strtotime($email_templates->updated_at);
            return ($email_templates->updated_at) ? '<span style="display:none">'.$date.'</span>' .date("d/m/Y",strtotime($email_templates->updated_at)) : '-';
          })
          ->addColumn('updated_at_normal', function ($email_templates) {
             
             return ($email_templates->updated_at) ? date("d/m/Y",strtotime($email_templates->updated_at)) : '-';
          })
          ->addColumn('action', function ($email_templates) {
              $edit_url = route('admin.emailTemplate.edit', $email_templates->id);
              return '<a class="btn action-edit btn-primary btn-action-icon edit-btn-clr" title="Edit" href="'.$edit_url.'">
              <span class="edit-icon"></span>
              </a>';
             
          })
          ->escapeColumns([])
          ->make(true);
    }

   
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display edit email template page
    ***/
    public function emailTemplateEdit($id)
    {
        $emailTemplate = EmailTemplate::findOrFail($id);
        return view('admin.email_templates.create')->with(compact('emailTemplate'));
    }

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Save updated email template details in database
    ***/
    public function emailTemplateUpdate(Request $request, $id)
    {
        if ($this->updateValidator($request)) {
            $input = $request->except(['_token','email']);
            $input['updated_at'] = now();

            $emailTemplate = EmailTemplate::findOrFail($id);
            
            $emailTemplate->update($input);

            /** Log activity **/
            Common::logActivity($emailTemplate , Auth::guard('admin')->user()->id , $emailTemplate->toArray() ,"Email template updated.");

            $notification = array(
                'message' => __('messages.emailTemplateUpdateSuccess'),
                'alert-type' => 'success'
              );

            return redirect()->route('admin.emailTemplate.index')->with($notification);
        }
    }

  
}
