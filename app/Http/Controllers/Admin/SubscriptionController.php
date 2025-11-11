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
use App\Models\Subscription;
use App\Models\SubscriptionType;
use Illuminate\Support\Str;
use App\Helpers\Common;
use Validator;
use DB;
use File;

class SubscriptionController extends Controller
{
    /***
    *   Developed by: dhruvish suthar
    *   Description: Display list event page
    ***/
    public function index()
    {
      return view('admin.subscription.index');
    }


    /***
    *   Developed by: dhruvish suthar
    *   Description: Get event list in datatable format to display in list page
    ***/
    public function subscriptionList()
    {
        $subscription = '';
        $subscription = Subscription::select('id','title','sub_title','amount','status','created_at')->orderBy('id','desc')->get();
        return datatables()->of($subscription)
          ->editColumn('status', function ($subscription) {
              $inactive_url = route('admin.subscription.inactive', $subscription->id);
              $active_url = route('admin.subscription.active', $subscription->id);

              if(Common::hasPermission(config('settings.admin_modules.subscription'),config('settings.permissions.status'))){
                  if($subscription->status=="active")
                  {
                      return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to deactivate" >Active</a>';
                  }
                  else
                  {
                      return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Inactive</a>';
                  }
              }
          })
            ->editColumn('created_at', function ($subscription) 
            {
                 return date('d/m/Y',strtotime($subscription->created_at));
            })
          ->escapeColumns([])
          ->addColumn('action', function ($subscription) {
                $edit_link = "";
                $delete_link = "";
                $edit_url = route('admin.subscription.edit', $subscription->id);
                $delete_url = route('admin.subscription.destroy', $subscription->id);

                if(Common::hasPermission(config('settings.admin_modules.subscription'),config('settings.permissions.edit'))){
                    $edit_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
                    <span class="edit-icon"></span>
                  </a>';
                }
                if(Common::hasPermission(config('settings.admin_modules.subscription'),config('settings.permissions.delete'))){
                    $delete_link = '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
                    <span class="delete-icon"></span>
                  </a>';
                }
                return $edit_link.''.$delete_link;
          })
          ->make(true);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display create subscription page
    ***/

    public function createSubscription()
    {
        $subscription = new Subscription;
        $subscriptionType = SubscriptionType::where('status','active')->orderBy('id','desc')->get();
        return view('admin.subscription.create')->with(compact('subscription','subscriptionType'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save Subscription in database
    ***/
    public function storeSubscription(Request $request)
    {
        $validation = Validator::make($request->all(), [
                'title' => 'required|unique:subscriptions,title,' . $request->id,
                'amount' => 'required',
                'subscription_type' => 'required',
                'description' => 'required',
            ]);
            
            if ($validation->fails()) {
                return redirect()->back()
                    ->withErrors($validation)
                    ->withInput();
            }

            if(count($request->services) > 0){

              $str_arr = implode (",", $request->services); 
            }else{

              $str_arr = '';
            }
            
             $input['title'] = $request->title;
             $input['sub_title'] = $request->sub_title;
             $input['amount'] = $request->amount;
             $input['per_year_amount'] = $request->per_year_amount;
             $input['subscription_type'] = $request->subscription_type;
             $input['product_id'] = $request->product_id;
             $input['description'] = $request->description;
             $input['detail_description'] = $request->detail_description; 
             $input['detail_page_message'] = $request->detail_page_message;
             $input['try_bottom_button_text'] = $request->try_bottom_button_text;
             $input['services'] = $str_arr;

             $subscription_store = Subscription::create($input);

              $notification = array(
                'message' =>  __('Subscription created successfully.'),
                'alert-type' => 'success'
              );

        return redirect()->route('admin.subscription.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display edit Subscription
    ***/
    public function SubscriptionEdit($id)
    {
        $subscription  = Subscription::findOrFail($id);
        $subscriptionType = SubscriptionType::where('status','active')->orderBy('id','desc')->get();
        $subscription_ser = explode (",", $subscription->services); 

        return view('admin.subscription.create')->with(compact('subscription','subscription_ser','subscriptionType'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save updated subscription details in database
    ***/
    public function SubscriptionUpdate(Request $request, $id)
    {
      //dd($request->all());
             $validation = Validator::make($request->all(), [
                'title' => 'required|unique:subscriptions,title,' . $request->id,
                'amount' => 'required',
                'subscription_type' => 'required',
                'description' => 'required',
            ]);
            
            if ($validation->fails()) {
                return redirect()->back()
                    ->withErrors($validation)
                    ->withInput();
            }

            $subscription = Subscription::findOrFail($id);

            if(count($request->services) > 0){

              $str_arr = implode (",", $request->services); 
            }else{

              $str_arr = '';
            }
            
             $input['title'] = $request->title;
             $input['sub_title'] = $request->sub_title;
             $input['amount'] = $request->amount;
             $input['per_year_amount'] = $request->per_year_amount;
             $input['subscription_type'] = $request->subscription_type;
             $input['product_id'] = $request->product_id;
             $input['description'] = $request->description;
             $input['detail_description'] = $request->detail_description;
             $input['detail_page_message'] = $request->detail_page_message;
             $input['try_bottom_button_text'] = $request->try_bottom_button_text;
             $input['services'] = $str_arr;

            $subscription->update($input);

            /** Log activity **/

            $notification = array(
                'message' => __('Subscription updated successfully.'),
                'alert-type' => 'success'
              );

            return redirect()->route('admin.subscription.index')->with($notification);
        
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Delete subscription
    ***/
    public function subscriptionDestroy($id)
    {
        $subscription = Subscription::findOrFail($id);

        $subscription->delete();

        $notification = array(
            'message' =>  __('Subscription deleted successfully.'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.subscription.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Deactivate subscription
    ***/
    public function subscriptionInactive($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->status ='inactive';
        $subscription->save();


        $notification = array(
            'message' =>  __('Subscription deactivated successfully.'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.subscription.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Activate subscription
    ***/
    public function subscriptionActive($id)
    {
       $subscription = Subscription::findOrFail($id);
        $subscription->status ='active';
        $subscription->save();


        $notification = array(
          'message' =>  __('Subscription activated successfully.'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.subscription.index')->with($notification);
    }
   
}
