<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\ActivityLog;
use App\Exports\ActivityLogExport;
use App\Helpers\Common;

use DB;

class ActivityLogController extends Controller
{
   

   /***
    *   Developed by: Khushbu Jajal
    *   Description: Display activity log
    ***/
    public function index()
    {
        
        return view('admin.activity_log.index');
    }
   
   /***
    *   Developed by: Khushbu Jajal
    *   Description: Get activity log list in datatable format to display in list page
    ***/
    public function getActivityLogList(Request $request)
    {
        $activityLog = '';
        $activityLog = ActivityLog::getHistory();

        $datatable = datatables()->of($activityLog)
          
          ->escapeColumns([])
          ->editColumn('properties', function ($activityLog) {
              if($activityLog['properties'])
              {
                $properties = $activityLog['properties'];
                $html = "<div class='attachments-list'>";
                $i=0;
                foreach($properties as $key => $property)
                {   
                    // $property = (is_array($property))? json_encode($property) : 'NO';
                    // $property = (is_object($property))? 'OBJECT' : 'NO';
                    $i++;
                    // $html.="<span>".$key." : ".$property."</span>";
                    if($i<=2)
                        $html.="<span>".$key." : ".$property."</span>";
                    else
                        $html.="<span style='display:none'>".$key." : ".$property."</span>";
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
                    
                        $html.=$key." : ".$property."\n";
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
         
        });
        if(Common::hasPermission(config('settings.admin_modules.activity_log'), config('settings.permissions.delete'))) {
            $datatable->addColumn('action', function ($activityLog) {
                $delete_url = route('admin.activity_log.delete', $activityLog['id']);
            
                return '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
                <span class="delete-icon"></span>
                </a>';
            
            });
        }
        return $datatable->make(true);
    }


    /***
    *   Developed by: Khushbu Jajal
    *   Description: Delete all logs
    ***/
    public function deleteAll()
    {
        ActivityLog::query()->delete();
        
        $notification = array(
            'message' =>  __('messages.allLogDeleteSuccess'),
            'alert-type' => 'success'
          );

        return back()->with($notification);
    }

    /***
    *   Developed by: Khushbu Jajal
    *   Description: Export all logs
    ***/
    public function exportLog(Request $request)
    {
        $startDate = isset($request->from_date) && $request->from_date ? $request->from_date : null;
        $endDate = isset($request->to_date) && $request->to_date ? $request->to_date : null;
        return Excel::download(new ActivityLogExport($startDate, $endDate), 'ActivityLog.xls');
      
    }

     /***
    *   Developed by: Khushbu Jajal
    *   Description: Delete log by id
    ***/
    public function deleteLog($id)
    {
       
        $log = ActivityLog::findOrFail($id);
        $log->delete();
       
        $notification = array(
            'message' =>  __('messages.logDeleteSuccess'),
            'alert-type' => 'success'
          );

        return back()->with($notification);
    }
}
