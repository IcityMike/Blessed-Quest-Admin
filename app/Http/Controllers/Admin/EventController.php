<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\ReferralPartner;
use App\Models\Events;
use App\Models\Librarys;
use Illuminate\Support\Str;
use App\Helpers\Common;
use Validator;
use DB;
use File;

class EventController extends Controller
{
    /***
    *   Developed by: dhruvish suthar
    *   Description: Display list event page
    ***/
    public function index()
    {
      return view('admin.event.index');
    }


    /***
    *   Developed by: dhruvish suthar
    *   Description: Get event list in datatable format to display in list page
    ***/
    public function eventList()
    {
        $event = '';
        $event = Events::select('id','event_name','library_id','admin_id','status','created_at')->orderBy('id','desc')->get();
        return datatables()->of($event)
          ->editColumn('status', function ($event) {
              $inactive_url = route('admin.event.inactive', $event->id);
              $active_url = route('admin.event.active', $event->id);

              if(Common::hasPermission(config('settings.admin_modules.events'),config('settings.permissions.status'))){
                  if($event->status=="active")
                  {
                      return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to deactivate" >Active</a>';
                  }
                  else
                  {
                      return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Inactive</a>';
                  }
              }
          })
            ->editColumn('created_at', function ($event) 
            {
                 return date('d/m/Y',strtotime($event->created_at));
            })
          ->escapeColumns([])
          ->addColumn('library_id', function ($event) {

                 $libraryget_name = Librarys::select('id','name','status')->where('id',$event->library_id)->where('status','active')->first();
                 if($libraryget_name){

                    $libraryname = $libraryget_name->name;
                 }else{

                    $libraryname = "";
                 }

                
                return $libraryname;
            
            })
          ->addColumn('action', function ($event) {
                $edit_link = "";
                $delete_link = "";
                $edit_url = route('admin.event.edit', $event->id);
                $delete_url = route('admin.event.destroy', $event->id);
               
              //   $link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
              //   <span class="edit-icon"></span>';
              
              // if(auth()->guard('admin')->user()->type != "sub")
              // {
              //   $link .= '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
              //       <span class="delete-icon"></span>
              //   </a> </a> ';
              // }
              // return $link;

              if(Common::hasPermission(config('settings.admin_modules.events'),config('settings.permissions.edit'))){
                  $edit_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
                  <span class="edit-icon"></span>
                </a>';
              }
              // if(Common::hasPermission(config('settings.admin_modules.events'),config('settings.permissions.delete'))){
              //     $delete_link = '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
              //     <span class="delete-icon"></span>
              //   </a>';
              // }
              return $edit_link.''.$delete_link;
             
          })
          ->make(true);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display create event page
    ***/

    public function createEvent()
    {
        $events = new Events;

        $libraryget = Librarys::select('id','name','status')->where('status','active')->get();
        return view('admin.event.create')->with(compact('events','libraryget'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save event in database
    ***/
    public function storeEvent(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'event_name' => 'required|string|unique:events,event_name|max:255',
            'library_id' => 'required',
        ]);
        
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        //store  here
        $input = $request->except(['_token','profile_picture','password']);
  
        $input['event_name'] = $request->event_name;
        $input['library_id'] = $request->library_id;
        $input['admin_id'] = Auth::guard('admin')->user()->id;
        $input['description'] = $request->description;
        /*** If profile picture is uploaded, save it to admin_pictures folder ***/

        $events_store = Events::create($input);

        $notification = array(
          'message' =>  __('Event created successfully.'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.event.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display edit team member page
    ***/
    public function EventEdit($id)
    {
        $events  = Events::findOrFail($id);

        $libraryget = Librarys::select('id','name','status')->where('status','active')->get();
        return view('admin.event.create')->with(compact('events','libraryget'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save updated event details in database
    ***/
    public function EventUpdate(Request $request, $id)
    {

      //dd($request->all());
             $validation = Validator::make($request->all(), [
                'event_name' => 'required|unique:events,event_name,' . $request->id,
                'library_id' => 'required',
            ]);
            
            if ($validation->fails()) {
                return redirect()->back()
                    ->withErrors($validation)
                    ->withInput();
            }

            $input = $request->except(['_token','profile_picture','email']);

            $events = Events::findOrFail($id);

             $input['event_name'] = $request->event_name;
             $input['library_id'] = $request->library_id;
             $input['description'] = $request->description;

            $events->update($input);

            /** Log activity **/

            $notification = array(
                'message' => __('Event updated successfully.'),
                'alert-type' => 'success'
              );

            return redirect()->route('admin.event.index')->with($notification);
        
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Delete event
    ***/
    public function eventDestroy($id)
    {
        $events = Events::findOrFail($id);

        $events->delete();

        $notification = array(
            'message' =>  __('Event deleted successfully.'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.event.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Deactivate event
    ***/
    public function eventInactive($id)
    {
        $events = Events::findOrFail($id);
        $events->status ='inactive';
        $events->save();


        $notification = array(
            'message' =>  __('Event deactivated successfully.'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.event.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Activate event
    ***/
    public function eventActive($id)
    {
       $events = Events::findOrFail($id);
        $events->status ='active';
        $events->save();


        $notification = array(
          'message' =>  __('Event activated successfully.'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.event.index')->with($notification);
    }
   
}
