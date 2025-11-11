<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Notification;
use App\Models\Admin;
use App\Helpers\Common;


class NotificationController extends Controller
{

    /***
    *   Developed by: Radhika Savaliya
    *   Description: Display notifications page
    ***/
    public function getNotifications()
    {
        return view('admin.notifications.index');
    }

     /***
    *   Developed by: Radhika Savaliya
    *   Description: Get notifications list in datatable format to display in list page
    ***/
    public function getNotificationsList()
    {
        
        $notifications = Notification::getAllNotifications(config('settings.user_types.admin'), Auth::guard('admin')->user()->id);

        return datatables()->of($notifications)
            ->editColumn('date', function ($notifications) {
                
                return '<span style="display:none">'.$notifications['timestamp'].'</span>' . $notifications['date'];
            
            })
            ->addColumn('date_normal', function ($notifications) {
                return $notifications['date'];
            
            })
          ->escapeColumns([])
          ->make(true);
    }

    
    /***
    *   Developed by: Radhika Savaliya
    *   Description: Delete all notifications for user
    ***/
    public function deleteAllNotifications()
    {
        
        Admin::find(Auth::guard('admin')->user()->id)->notifications()->delete();
        $notification = array(
            'message' =>  __('messages.notificationDeleteSuccess'),
            'alert-type' => 'success'
        );

        return back()->with($notification);
    }




    
}
