<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\ActivityLog;
use App\Helpers\Common;
use App\Exports\ActivityLogExport;

use DB;

class ActivityLogController extends Controller
{
   

   /***
    *   Developed by: Dhruvish Suthar
    *   Description: Display activity log
    ***/
    public function index()
    {
        if(Common::hasPermission(config('settings.admin_modules.activity_log'),config('settings.permissions.view'))){
            return view('admin.activity_log.index');
        }else{
            abort(401);
        }
    }
   
   /***
    *   Developed by: Dhruvish Suthar
    *   Description: Get activity log list in datatable format to display in list page
    ***/
    public function getActivityLogList(Request $request)
    {
        $activityLog = '';
        $activityLog = ActivityLog::getHistory();

        return datatables()->of($activityLog)
          
          ->escapeColumns([])
          ->editColumn('properties', function ($activityLog) {
              if($activityLog['properties'])
              {
                $properties = $activityLog['properties'];
                $html = "<div class='attachments-list'>";
                $i=0;
                foreach($properties as $key => $property)
                {   
                    if(is_string($property)){
                        $i++;
                        if($i<=2)
                            $html.="<span>".$key." : ".$property."</span>";
                        else
                            $html.="<span style='display:none'>".$key." : ".$property."</span>";
                    }
                    
                }
                if(count($properties) > 2)
                {
                    $html.='<a href="JavaScript:void(0)" class="read-more1">Read more</a>';
                }
                $html .= "</div>";
                return $html;
              }
              else
              {
                return "-";
              }    
          })
          ->addColumn('properties_normal', function ($activityLog) {
                if($activityLog['properties'])
                {
                    $properties = $activityLog['properties'];
                    $html = "";
                    foreach($properties as $key => $property)
                    {
                        if(is_string($property)){
                            $html.=$key." : ".$property."\n";
                        }
                    }
                    return $html;
                }
                else
                {
                    return "-";
                }
           
          })
          ->editColumn('created_at', function ($activityLog) {
            $date = str_replace('/', '-',$activityLog['created_at']);
            $date = strtotime( $date);
            return '<span style="display:none">'.$date.'</span>' . $activityLog['created_at'];
           
         })
        ->addColumn('created_at_normal', function ($activityLog) {
          return $activityLog['created_at'];
         
        })
        ->addColumn('action', function ($activityLog) {
            $delete_route = route('admin.activity_log.delete', $activityLog['id']);
            $delete_url = '';
            if(auth()->guard('admin')->user()->type != "sub"){
                $delete_url = '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_route.'")>
              <span class="delete-icon"></span>
            </a>';
            }
            
           
            return $delete_url;
           
        })
        ->make(true);
    }


    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Delete all logs
    ***/
    public function deleteAll()
    {
        ActivityLog::query()->delete();
        
        /** Log activity **/
        Common::logActivity(new ActivityLog(), Auth::guard('admin')->user()->id , null ,"Activity logs deleted.");
        
        $notification = array(
            'message' =>  __('messages.allLogDeleteSuccess'),
            'alert-type' => 'success'
          );

        return back()->with($notification);
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Export all logs
    ***/
    public function exportLog(Request $request)
    {
        $startDate = isset($request->from_date) && $request->from_date ? $request->from_date : null;
        $endDate = isset($request->to_date) && $request->to_date ? $request->to_date : null;
        return Excel::download(new ActivityLogExport($startDate, $endDate), 'ActivityLog.xls');
      
    }

     /***
    *   Developed by: Dhruvish Suthar
    *   Description: Delete log by id
    ***/
    public function deleteLog($id)
    {
        $log = ActivityLog::findOrFail($id);
        
        /** Log activity **/
        Common::logActivity($log , Auth::guard('admin')->user()->id , $log->toArray() ,"Activity logs deleted.");
        
        $log->delete();
       
        $notification = array(
            'message' =>  __('messages.logDeleteSuccess'),
            'alert-type' => 'success'
          );

        return back()->with($notification);
    }
}
