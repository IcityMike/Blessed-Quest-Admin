<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\ClientBeneficiaries;
use App\Models\Librarys;
use App\Models\SubscriptionType;
use App\Models\User;
use App\Models\Types_of_voice;
use App\Helpers\Common;
use DB;
use Validator;
use File;

class Types_subscriptionController extends Controller
{
    /***
    *   Developed by: dhruvish suthar
    *   Description: Display list Subscription type
    ***/
    public function index()
    {
        return view('admin.subscription_type.index');
    }


    public function list()
    {
        $types_subscription = '';
        $types_subscription = SubscriptionType::select('id','subscription_type','status','created_at')->orderBy('id','desc')
          ->get();

        return datatables()->of($types_subscription)
            ->editColumn('status', function ($types_subscription) {
                $inactive_url = route('admin.subscription_type.block', $types_subscription->id);
                $active_url = route('admin.subscription_type.active', $types_subscription->id);

                if(Common::hasPermission(config('settings.admin_modules.voice_type'),config('settings.permissions.status'))){

                    if($types_subscription->status=='active')
                    {
                            return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to add in Black List" >Active</a>';
                    }
                    else
                    {
                        return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Black Listed</a>';
                    }
                }
            })
            ->editColumn('created_at', function ($types_subscription) 
            {
                return date('d/m/Y',strtotime($types_subscription->created_at));
            })
            ->escapeColumns([])
            ->addColumn('action', function ($types_subscription) 
            {

                $edit_link = "";
                $delete_link = "";
                $edit_url = route('admin.subscription_type.edit', $types_subscription->id);
                $delete_url = route('admin.subscription_type.destroy', $types_subscription->id);

                 if(Common::hasPermission(config('settings.admin_modules.voice_type'),config('settings.permissions.edit'))){

                    $edit_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
                    <span class="edit-icon"></span>
                  </a>';

                }
                if(Common::hasPermission(config('settings.admin_modules.voice_type'),config('settings.permissions.delete'))){

                    $delete_link = '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
                    <span class="delete-icon"></span>
                  </a>';

                }
              return $edit_link.''.$delete_link;

            })
          ->make(true);
    }

    public function view($subscriptionId)
    {
        $types_subscription = SubscriptionType::where('id',$subscriptionId)->first();
        $data['types_of_voice'] = $types_subscription;
        return view('admin.type_of_voice.view',$data);
    }

    public function type_of_SubscriptionBlock(Request $reques,$id)
    {
        $subscription = SubscriptionType::findOrFail($id);
        $subscription->status = 'inactive';
        $subscription->save();

       // dd($librarys,'hg');
        /** Log activity **/

        $notification = array(
          'message' =>  __('Subscription Type deactivated successfully.'),
          'alert-type' => 'success'
        );

        // return redirect()->route('admin.beneficiars.index')->with($notification);
        return Redirect::back()->with($notification);
    }


    public function Subscription_typeActive(Request $request,$id)
    {
        $subscription = SubscriptionType::findOrFail($id);
        $subscription->status = 'active';
        $subscription->save();

       // dd($librarys,'fggg');

        $notification = array(
          'message' =>  __('Subscription activated successfully.'),
          'alert-type' => 'success'
        );

        // return redirect()->route('admin.beneficiars.index')->with($notification);
        return Redirect::back()->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display create Subscription type
    ***/

    public function createSubscription_type()
    {
       $subscription = new SubscriptionType;
        return view('admin.subscription_type.create')->with(compact('subscription'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save Subscription type in database
    ***/
    public function storeSubscription_type(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'subscription_type' => 'required|string|unique:subscription_types,subscription_type|max:255',
        ]);
        
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }

        //store  here
      
        $input['subscription_type'] = $request->subscription_type;
        $subscription = SubscriptionType::create($input);

        /** Log activity **/

        $notification = array(
          'message' =>  __('Type created successfully.'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.subscription_type.index')->with($notification);
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Display edit Subscription type
    ***/
    public function Subscription_typeEdit($id)
    {
        $subscription  = SubscriptionType::findOrFail($id);
        return view('admin.subscription_type.create')->with(compact('subscription'));
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Save updated Subscription type
    ***/
    public function Subscription_typeUpdate(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'subscription_type' => 'required|string|unique:subscription_types,subscription_type,' . $request->id,
        ]);
        
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        
        $types_update = SubscriptionType::findOrFail($id);

        $input['subscription_type'] = $request->subscription_type;
       
        $types_update->update($input);

        /** Log activity **/
      
        $notification = array(
            'message' => __('Subscription type updated successfully.'),
            'alert-type' => 'success'
          );

        return redirect()->route('admin.subscription_type.index')->with($notification);
            
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Delete Subscription type
    ***/
    public function Subscription_typeDestroy($id)
    {
        $Subscription = SubscriptionType::findOrFail($id);

        $Subscription->delete();

        $notification = array(
            'message' =>  __('Subscription deleted successfully.'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.subscription_type.index')->with($notification);
    }


}