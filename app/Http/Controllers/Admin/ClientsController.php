<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Traits\UserPushNotificationTrait;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ClientBeneficiaries;
use App\Exports\ClientsExport;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Helpers\Common;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use File;
use DB;

class ClientsController extends Controller
{
    use UserPushNotificationTrait;
    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Display list client users page
    ***/
    public function index(Request $request)
    {
        $refferal_id = null;
        if($request->refId){
            $refferal_id = $request->refId;
        }
        return view('admin.clients.index',compact('refferal_id'));
    }

   /***
    *   Developed by: Dhruvish Suthar
    *   Description: Validate create client user request
    ***/
    public function validator($request)
    {
        return $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10',
            'email' => 'required|email|max:255|unique:users,deleted_at,NULL',
         //'password' => 'required|min:6|confirmed', 
      ]);
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Validate update admin request
    ***/
    public function updateValidator($request)
    {
        return  $this->validate($request, [
            'name' => 'required|string|max:255',
            //'last_name' => 'required|string|max:255',
            'phone_number' => 'required|digits:10|min:10',
            // 'password' => 'confirmed',
       ]);
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Get Clients list in datatable format to display in list page
    ***/
    public function clientList(Request $request)
    {
        $reffered_by = ($request->ref_id) ? $request->ref_id : null;
        // dd($reffered_by);
        $client = '';
        if($reffered_by){
            $client = User::select('id','name',DB::raw("CONCAT(`first_name`,' ',`last_name`) AS full_name"),'email','phone_number','reffered_by','status')
                ->where(function ($query) use ($reffered_by) {
                    $query->where('reffered_by',$reffered_by);
                })
            ->orderBy('id','desc')
            ->get();
            
        }else{
            $client = User::select('id','name',DB::raw("CONCAT(`first_name`,' ',`last_name`) AS full_name"),'email','phone_number','reffered_by','status')
            ->orderBy('id','desc')
            ->get();
        }
        
        return datatables()->of($client)
             ->editColumn('name',function ($client){
               // return $client->name;
                if($client->name){//ucfirst

                      return  ucfirst($client->name);
                  }
                  else{
                      return '';
                  }
            })
           
            ->editColumn('status', function ($client) {
                $inactive_url = route('admin.clients.block', $client->id);
                $active_url = route('admin.clients.active', $client->id);

                if(Common::hasPermission(config('settings.admin_modules.clients'),config('settings.permissions.status'))){
                    if($client->status=="active")
                    {
                            return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to deactivate" >Active</a>';
                    }
                    else
                    {
                        return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Inactive</a>';
                    }
                }
            })
            ->editColumn('swiftstatus', function ($client) {
                $inactive_url = route('admin.clients.block', $client->id);
                $active_url = route('admin.clients.active', $client->id);
                if($client->swift_user_status=="1")
                {
                        return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to deactivate" >Verified</a>';
                }
                else if($client->swift_user_status=="2")
                {
                        return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to deactivate" >Verification Pending</a>';
                }
                else
                {
                    return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Pending</a>';
                }
            })
            ->escapeColumns([])
            ->addColumn('action', function ($client) {
                $edit_link = "";
                $delete_link = "";
                $view_link = "";
                $edit_url = route('admin.clients.edit', $client->id);
                $view_url = route('admin.clients.view', $client->id);
                $delete_url = route('admin.clients.destroy', $client->id);
                $get_swiftDetail_url = "#";
                if(Common::hasPermission(config('settings.admin_modules.clients'),config('settings.permissions.edit'))){
                    $edit_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="Edit" href="'.$edit_url.'">
                    <span class="edit-icon"></span>
                    </a>';
                } 

                // if(Common::hasPermission(config('settings.admin_modules.clients'),config('settings.permissions.delete'))){
                //     $delete_link = '<a class="btn btn-danger btn-action-icon action-delete delete-btn-clr" title="Delete" href="JavaScript:Void(0);" data-toggle="modal" data-target="#deleteModal" onclick=deleteData("'.$delete_url.'")>
                //             <span class="delete-icon"></span>
                //           </a>';
                // }

                if(Common::hasPermission(config('settings.admin_modules.clients'),config('settings.permissions.view'))){
                    // $view_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="View user\'s details" data-toggle="modal" data-target="#userDetailModal" data-clientId="'.$client->id.'" onclick=getUserData("'.$client->id.'")><span class="eye-icon"></span></a>';
                    $view_link = '<a class="btn btn-primary btn-action-icon action-edit edit-btn-clr" title="View" href="'.$view_url.'">
                    <span class="eye-icon"></span>
                    </a>';
                }
                $link = $edit_link.' '.$delete_link.' '.$view_link;
            
                return $link;
             
            })
            ->make(true);
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Display create Client page
    ***/
    public function createClient()
    {
        $client = new User;
        return view('admin.clients.create')->with(compact('client'));
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Crop and upload Profile picture and return uploaded image name
    ***/
    public function cropProfilePicture(Request $request)
    {
        $data = $request->image;
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $fileName = "picture_".time().".". 'png';
        //$imageName ='public/'.config('settings.admin_picture_folder')."/".$fileName;
        $imageName =config('settings.client_picture_folder')."/".$fileName;


     //   file_put_contents($imageName, $data);

      //  \File::put($imageName. '/' . $imageName, $data);
        \File::put($imageName, $data);
        echo $fileName;
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Save client in database
    ***/
    public function storeClient(Request $request)
    {
        
        $validation = Validator::make($request->all(), [
            'swift_user_type' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|digits:10|min:10',
            'email' => 'required|max:255|unique:users,email,NULL,id,deleted_at,NULL',
        ]);
        if ($validation->fails()) {
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }
        $input = $request->except(['_token']);
        $password =  Str::random(6);

        $input['name'] = $input['first_name'].' '.$input['last_name'];
        $input['password'] = bcrypt($password);
        $input['date_of_birth'] = ($request->date_of_birth) ? Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d') : null;

        $input['status'] = 'active';
        $input['swift_user_uniqueId'] = uniqid();
        $input['swift_user_status'] = 0;

        /*** If profile picture is uploaded, save it to admin_pictures folder ***/

        if($request->profile_picture_hidden)
        {  
           $input['profile_picture'] = $request->profile_picture_hidden;
        }

        $client = User::create($input);
        $m_data = [
            'bankaccountName' => $input['name'],
            'isActive' => 'true'
        ];
        // create automatcher account in 

         /*** Send welcome email to user by email ***/
        $data = User::find($client->id)->toArray();
        $data['clientId'] = $client->id;
        $data['password'] = $password;
        $data['name'] = $input['first_name'].' '.$input['last_name'];
        
        $client->sendWelcomeNotification($data);

        //send login detail via twilio sms to client
        $sms_data['user_id'] = $client->id;
        $sms_data['title']='Your Login Credentials';
        $sms_data['userName']= $client->email;
        $sms_data['password']= $password;
        $sms_data['notificationType']="login_details";
        $sms_data['type']="api";
        $this->sendUserPushTrait($sms_data);

        /** Log activity **/
        Common::logActivity($client , Auth::guard('admin')->user()->id , $client->toArray() ,"Client user created.");
       
        $notification = array(
          'message' =>  __('messages.clientAddSuccess'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.clients.index')->with($notification);
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Display edit client user page
    ***/
    public function editClient($id)
    {
        $client  = User::findOrFail($id);
        return view('admin.clients.create')->with(compact('client'));
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Save updated client user details in database
    ***/
    public function updateClient(Request $request, $id)
    {
        if ($this->updateValidator($request)) {
            $input = $request->except(['_token']);

            $client = User::findOrFail($id);

            $input['date_of_birth'] = ($request->date_of_birth) ? Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d') : null;
            //$input['name'] = $input['first_name'].' '.$input['last_name'];
            $input['name'] = $input['name'];
            $input['password'] = (isset($input['password']) && !empty($input['password'])) ? bcrypt($input['password']) : $client->password;

            /*** If profile picture is uploaded, save it to admin_pictures folder ***/

            if($request->profile_picture_hidden)
            {  
                if($client->profile_picture)
                {
                    $file = config('settings.client_picture_folder')."/".$client->profile_picture;
                    if(File::exists($file)) {
                        File::delete($file);
                    }
                }
               
                $input['profile_picture'] = $request->profile_picture_hidden;
            }

            $client->update($input);

            /** Log activity **/
          //  Common::logActivity($client , Auth::guard('admin')->user()->id , $client->toArray() ,"Client user details updated.");
            //dd($request->all());

            Common::common_for_user_notification($id); 

            $notification = array(
                'message' => __('messages.clientUpdateSuccess'),
                'alert-type' => 'success'
            );

            return redirect()->route('admin.clients.index')->with($notification);
        }
    }

    public function viewClient(Request $request,$id){
        $client = User::find($id);
        if($client){
            // dd($client);
            $response = NULL;
            if($client->swift_user_type == "individual"){
                $data = array();
                $details = json_decode($client->individual_properties);
                if(!empty($details)){
                    $data['terms'] = $details->AcceptedTerms;
                    $data['applicants'] = $details->Applicants;
                    $data['uploadedDocuments'] = $details->UploadedDocuments;
                }
                $response['modal_title'] = $client->name.' - '.$client->email;
                $response['html'] = view('admin.clients.load_individual_detail', compact('client','data','details'))->render();
                
            }elseif($client->swift_user_type == "joint"){

                $data = array();
                $details = json_decode($client->joint_properties);
                if(!empty($details)){
                    $data['terms'] = $details->AcceptedTerms;
                    $data['applicants'] = $details->Applicants;
                    $data['uploadedDocuments'] = $details->UploadedDocuments;
                }
                $response['modal_title'] = $client->name.' - '.$client->email;
                $response['html'] = view('admin.clients.load_joint_detail', compact('client','data','details'))->render();

            }elseif($client->swift_user_type == "company"){
                
                $data = array();
                $details = json_decode($client->company_properties);
                if(!empty($details)){
                    $data['terms'] = $details->AcceptedTerms;
                    $data['applicants'] = $details->Applicants;
                    $data['CompanyOwners'] = $details->OtherCompaniesAsCompanyOwners;
                    $data['uploadedDocuments'] = $details->UploadedDocuments;
                }
                $response['modal_title'] = $client->name.' - '.$client->email;
                $response['html'] = view('admin.clients.load_company_detail', compact('client','data','details'))->render();
                
            }elseif($client->swift_user_type == "corporatetrust"){
                $data = array();
                $details = json_decode($client->corporatetrust_properties);
                if(!empty($details)){
                    $data['terms'] = $details->AcceptedTerms;
                    $data['applicants'] = $details->Applicants;
                    $data['AdditionalTrustBeneficiaries'] = $details->AdditionalTrustBeneficiaries;
                    $data['CompanyOwners'] = $details->OtherCompaniesAsCompanyOwners;
                    $data['uploadedDocuments'] = $details->UploadedDocuments;
                }
                $response['modal_title'] = $client->name.' - '.$client->email;
                $response['html'] = view('admin.clients.load_corporatetrust_detail', compact('client','data','details'))->render();
                
            }elseif($client->swift_user_type == "individualtrust"){
                $data = array();
                $details = json_decode($client->individualtrust_properties);
                if(!empty($details)){
                    $data['terms'] = $details->AcceptedTerms;
                    $data['applicants'] = $details->Applicants;
                    $data['AdditionalTrustBeneficiaries'] = $details->AdditionalTrustBeneficiaries;
                    $data['CompanyOwners'] = $details->OtherCompaniesAsTrustOwners;
                    $data['uploadedDocuments'] = $details->UploadedDocuments;
                }
                $response['modal_title'] = $client->name.' - '.$client->email;
                $response['html'] = view('admin.clients.load_individualtrust_detail', compact('client','data','details'))->render();
                
            }
            // else{
            //     return response()->json([
            //         'status'=>false,
            //         'message' => 'user swiftUserType not found.'
            //     ], 404);
            // }
            
            return view('admin.clients.view')->with(compact('client','response')); 
        }

    }

    // public function getBeneficiars(Request $request,$id)
    // {
    //     $beneficiars = '';
    //     $beneficiars = ClientBeneficiaries::select('id','user_id','name','email','contact_number','created_at','status')->where('user_id',$id)->orderBy('id','desc')
    //       ->get();
        
    //     return datatables()->of($beneficiars)
    //         ->editColumn('user_name', function ($beneficiars) 
    //         {
    //             $user = User::select('name')->where('id',$beneficiars->user_id)->first();
    //             return ($user) ? ucwords($user->name) : '';
    //         })
    //         ->editColumn('status', function ($beneficiars) {
    //             $inactive_url = route('admin.beneficiars.block', $beneficiars->id);
    //             $active_url = route('admin.beneficiars.active', $beneficiars->id);
    //             if($beneficiars->status==1)
    //             {
    //                 return '<a class="active-status inactive_button" href="'.$inactive_url.'" title="Click here to add in Black List" >Active</a>';
    //             }
    //             else
    //             {
    //                 return '<a class="inactive-status active_button" href="'.$active_url.'" title="Click here to activate" >Black Listed</a>';
    //             }
    //         })
    //         ->editColumn('created_at', function ($beneficiars) 
    //         {
    //             return date('d/m/Y',strtotime($beneficiars->created_at));
    //         })
    //         ->escapeColumns([])
    //         ->addColumn('action', function ($beneficiars) 
    //         {
    //             $view_url = route('admin.beneficiars.view', $beneficiars->id);
    //             return '<a class="action-view btn btn-primary btn-action-icon action-edit edit-btn-clr" href="'.$view_url.'"><span class="eye-icon"></span></a>';
    //         })
    //       ->make(true);
    // }

    public function sentTransactions(Request $request,$id)
    {
        $transactions = '';
        $transactions = Transaction::select('id','user_name','beneficiar_name','beneficiar_email','beneficiary_contact_number','transaction_amount','transaction_created_at','source_currency','destination_currency','transaction_status','transaction_fee','destination_transaction_amount','transaction_id','closing_balance')->where('user_id',$id)->orderBy('id','desc')
          ->get();
        
        return datatables()->of($transactions)
            ->editColumn('transaction_status', function ($transactions) 
            {
                return '<span style="color:'.Common::getTransactionStatusColor()[$transactions->transaction_status].'">'.config('settings.nium_transaction_status')[$transactions->transaction_status].'</span>';
            })
            ->editColumn('destination_transaction_amount', function ($transactions) 
            {
                return number_format($transactions->destination_transaction_amount,2).' '.$transactions->destination_currency;
            })
            ->editColumn('transaction_fee', function ($transactions) 
            {
                // return number_format($transactions->transaction_fee,2)." AUD";
                return ($transactions->transaction_fee) ? number_format($transactions->transaction_fee,2).' AUD' : '-';
            })
            ->editColumn('closing_balance', function ($transactions) 
            {
                return number_format($transactions->closing_balance,2)." AUD";
                // return ($transactions->closing_balance) ? number_format($transactions->closing_balance,2).' AUD' : '-';
            })
            ->editColumn('transaction_created_at', function ($transactions) 
            {
                return date('d/m/Y H:i:s',strtotime($transactions->transaction_created_at));
            })
            ->escapeColumns([])
            ->addColumn('action', function ($transactions) 
            {
                $view_url = route('admin.transactions.view', $transactions->id);
                return '<a class="action-view btn btn-primary btn-action-icon action-edit edit-btn-clr" href="'.$view_url.'"><span class="eye-icon"></span></a>';
            })
          ->make(true);
    }

    /***
    *   Developed by: Dhruvish Suthar
    *   Description: Delete client user
    ***/
    public function destroyClient($id)
    {
        $client = User::findOrFail($id);
        /** Log activity **/
        Common::logActivity($client , Auth::guard('admin')->user()->id , $client->toArray() ,"Client user record deleted.");

        $client->delete();

        $notification = array(
            'message' =>  __('messages.clientDeleteSuccess'),
            'alert-type' => 'success'
        );

        return redirect()->route('admin.clients.index')->with($notification);
    }

    public function clientBlock(Request $reques,$id)
    {
        $client = User::findOrFail($id);
       // $client->status ='block';
        $client->status ='inactive';
        $client->save();

        //send push notification to client for block by admin
        // $data['user_id'] = $client->id;
        // $data['title']='blocked user';
        // $data['text']='Your account is blocked by admin!';
        // $data['notificationType']="block_user";
        // $data['type']="block_user";
        // $this->sendUserPushTrait($data);

        /** Log activity **/
        Common::logActivity($client , Auth::guard('admin')->user()->id , $client->toArray() ,"Client status updated to inactive.");

        $notification = array(
          'message' =>  __('messages.clientDeactivateSuccess'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.clients.index')->with($notification);
    }

    public function clientActive(Request $request,$id)
    {
        $client = User::findOrFail($id);
        $client->status ='active';

        /** Log activity **/
        Common::logActivity($client , Auth::guard('admin')->user()->id , $client->toArray() ,"Admin status updated to active.");

        $client->save();

        $notification = array(
          'message' =>  __('messages.clientActivateSuccess'),
          'alert-type' => 'success'
        );

        return redirect()->route('admin.clients.index')->with($notification);
    }

    // public function exportClients(Request $request){

    //     return Excel::download(new ClientsExport($request->status), 'Clients-'.time().'.xls');
    // }
}
