<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ClientBeneficiaries;
use App\Models\UserDevice;
use App\Models\Notifications;
use App\Models\Events_user;
use App\Models\Events;
use App\Models\User_notifications_settings;
use App\Models\Review_rattings_stores;
use App\Models\Prayer_like_unlike;
use App\Models\Default_profile_picture;
use App\Models\Librarys;
use App\Models\Types_of_voice;
use App\Models\Transaction;
use App\Models\Librarys_audio;
use App\Models\PaymentDetails;
use App\Models\Subscription;
use App\Models\Destination_details;
use App\Models\Destination_details_img;
use App\Models\Source_details;
use App\Models\Source_details_img;
use App\Models\User_location_img;
use App\Models\Blessed_location_details;
use App\Models\User_location_details;
use App\Models\Blessed_location_imgs;
use App\Models\Blessed_location_list;
use App\Models\Subscription_user;
use App\Helpers\Common;
use App\Models\User;
use Carbon\Carbon;
use App\Jobs\ProcessImage;
use App\Jobs\DestinationImage;
use App\Jobs\ProcessImagemulty;
use Illuminate\Support\Facades\Storage;
use File;
use DB;

class UserController extends Controller
{
    
    protected function setData($value)
    {
        array_walk_recursive($value, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });
        return $value;;
    }

    public function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            'user_status' => $result->status,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

    public function update_user_voice_type(Request $request){

        $validator = Validator::make($request->all(), [
            'voice_type_id' => 'required',
        ]);
        
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $user = User::where('id',$request->user()->id)->first();

        $user['type_of_voice'] = $request->voice_type_id; 
        $user->update();


        $default_data = Common::getEventUser($user->id,"user_id");

        return response()->json([
                'status'=>true,
                'Events_data' => @$default_data,
                'message' => 'Voice type store successfully.',
            ], 200);
    }


    public function notification_count(Request $request){

        $getNotifications_count = Notifications::where('user_id',$request->user()->id)->where('notify_read_unread','1')->orderBy('id', 'desc')->get();

        $NotificationsCount = @$getNotifications_count->count();


        return response()->json([
            'status'=>true,
            'notification_count' => @$NotificationsCount,
            'message' => 'Notification count get successfully.',
        ], 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => false,
            //'message' => $errorMessages,
        ];
        // dd($errorMessages);
        if(!empty($errorMessages)){
            if(!is_array($errorMessages)){
                foreach($errorMessages->messages() as $err){
                    $response['message'] = $err[0];
                }

            }else{
                $msg = '';
                foreach($errorMessages as $err){
                    if(is_array($err)){
                        foreach ($err as $er) {
                            $msg .= $er.',';
                        }
                    }else{
                        $msg .= $err[0];
                    }
                }
                $response['message'] = $msg;
            }
        }else{
            $response['message'] = $error;
        }

        /*if(!empty($errorMessages)){
            $response['message'] = $errorMessages;
        }*/
        return response()->json($response, $code);
    }


    public function prayer_like_unlike(Request $request){

        $validator = Validator::make($request->all(), [
              'prayer_id' => 'required',
              'like_unlike' => 'required|in:0,1',
        ]);
        
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        ///////////////// count prayer and one prayer do not unlike /////////////////////////////
        $prayer_like_unlikecount = Prayer_like_unlike::where('like_unlike','1')->where('user_id',$request->user()->id)->count();

        // if($request->like_unlike == '0'){

        //     if($prayer_like_unlikecount == '1'){

        //         return response()->json([
        //             'status'=>true,
        //             'message' => 'You do not dislike this event.',
        //         ], 200);
        //     }
        // }

        //////////////////////////////////////////////////////////////////////////////////////

        $prayer_like_unlike = Prayer_like_unlike::where('prayer_id',$request->prayer_id)->where('user_id',$request->user()->id)->first();

        if($prayer_like_unlike){

            $prayer_like_unlike->like_unlike = $request->like_unlike;
            $prayer_like_unlike->update();
        }else{

            $p_like_unlike = Prayer_like_unlike::Create([
                'user_id' => @$request->user()->id,
                'prayer_id' => $request->prayer_id,
                'like_unlike' => $request->like_unlike,
            ]);

        }

        if($request->like_unlike == '1'){

            return response()->json([
                'status'=>true,
                'message' => 'Event Like successfully.',
            ], 200);

        }else{

            return response()->json([
                'status'=>true,
                'message' => 'Event Unlike successfully.',
            ], 200);
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: add Transactions
    ***/
    public function addTransactions(Request $request){

        $validator = Validator::make($request->all(), [
              'transaction_id' => 'required',
              'transaction_status' => 'required',
              'transaction_amount' => 'required',
        ]);
        
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        
        $input['user_id'] = @$request->user()->id;
        $input['transaction_id'] = $request->transaction_id;
        $input['transaction_status'] = $request->transaction_status;
        $input['transaction_amount'] = $request->transaction_status;
        $transaction_store = Transaction::create($input);

        return response()->json([
                'status'=>true,
                'message' => 'Transaction save successfully.!',
            ], 200);
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: subscription store
    ***/
    public function subscription_list(Request $request){

        // $validator = Validator::make($request->all(), [
        //       'transaction_id' => 'required',
        //       'subscription_type' => 'required',
        // ]);
        
        // if($validator->fails())
        // {
        //     return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        // }

        $subscriptionlist = Subscription::where('status','active')->orderBy('id', 'DESC')->get();

        $subscriptionData = array();
        foreach ($subscriptionlist as $subscriptionvalue) {
           
           $ll = $subscriptionvalue;

            if($ll['services'])
            {
                $str_arr = explode (",", $ll['services']); 

                $ll['services'] = $str_arr;

            }else{

                $ll['services'] = '';
            }

            $subscriptionData[] = $ll;
        }

        $subscription_user = Subscription_user::where('user_id',$request->user()->id)->where('status','active')->first();

        $today_date = date("Y-m-d");
        //dd($today_date,$subscription_user->cancel_date);
       
        if($subscription_user){

            if($subscription_user->cancel_date >= $today_date){

               // $active_status = 'active';
               $active_status = 'You have active subscriptions';
            }else{

                //$active_status = 'inactive';
                $active_status = 'You have no active subscriptions';
            }
        }else{

                //$active_status = 'inactive';
                $active_status = 'You have no active subscriptions';
            }

        if(count($subscriptionlist) == 0){

            return response()->json([
                'status'=>true,
                'data' => $subscriptionlist,
                'message' => 'No data found.!',
                'active_data' => @$active_status,
            ], 200);

        }else{

            return response()->json([
                'status'=>true,
                'data' => $subscriptionlist,
                'message' => 'Subscription list get successfully.',
                'active_data' => @$active_status,
            ], 200);
        }
    }

    public function cancel_subscription(Request $request){

        $validator = Validator::make($request->all(), [
              'subscription_id' => 'required',
        ]);
        
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $subscription_user = Subscription_user::where('id',$request->subscription_id)->where('user_id',$request->user()->id)->where('status','active')->first();

        if($subscription_user){

            $subscription_user->cancel_date = date("Y-m-d");
            $subscription_user->last_active_date = $subscription_user->end_date;
            $subscription_user->update();
            
            if($subscription_user->cancel_date){

                return response()->json([
                    'status'=>true,
                    'data' => $subscription_user,
                    'message' => 'You have already cancelled subscription.!',
                ], 200);

            }else{

                return response()->json([
                    'status'=>true,
                    'data' => $subscription_user,
                    'message' => 'Subscription cancelled successfully.!',
                ], 200);
            }

        }else{

                return response()->json([
                    'status'=>true,
                    'data' => @$subscription_user,
                    'message' => 'Subscription cancelled successfully.!',
                ], 200);
        }
    }

    public function endDate_subscription(Request $request){

        $subscription_user = Subscription_user::where('user_id',$request->user()->id)->where('status','active')->first();

        if($subscription_user){

            $today_date = date("Y-m-d");
           // dd($today_date,$subscription_user->end_date);
            if($subscription_user->end_date >= $today_date){

               $active_status = 'You have active subscriptions';

               return response()->json([
                    'status'=>true,
                    'data' => @$subscription_user,
                    'active_data' => @$active_status,
                    'end_date' => @$subscription_user->end_date,
                ], 200);

            }else{

                $subscription_user->status = 'inactive';
                $subscription_user->last_active_date = $subscription_user->end_date;
                $subscription_user->update();

                $active_status = 'You have no active subscriptions';

                return response()->json([
                    'status'=>true,
                    'data' => @$subscription_user,
                    'active_data' => @$active_status,
                    'end_date' => @$subscription_user->end_date,
                ], 200);
            }

        }else{

            return response()->json([
                    'status'=>true,
                    'data' => @$subscription_user,
                    'active_data' => 'You have no active subscriptions',
                    'end_date' => @$subscription_user->end_date,
                ], 200);
        }
        
    }

    public function user_subscription_details(Request $request){

        $subscription_user = Subscription_user::where('user_id',$request->user()->id)->where('status','active')->first();

            if(@$subscription_user->services)
            {
                @$str_arr = explode (",", @$subscription_user->services); 

               @$subscription_user->services = $str_arr;

            }

        $today_date = date("Y-m-d");
       
        if(@$subscription_user){
            //dd($subscription_user->cancel_date,$today_date);
            if(@$subscription_user->end_date >= $today_date){

                @$subscription_data = Subscription::where('id',@$subscription_user->subscription_id)->first();

                if(@$subscription_data->services)
                {
                    $str_arr = explode (",", @$subscription_data->services); 

                   @$subscription_data->services = @$str_arr;

                }

               // $active_status = 'active';
               $active_status = 'You have active subscriptions';

               $next_billing_date = @$subscription_user->cancel_date;

               $payment_methods = '';

               $active_subscriptions = 'true';
            }else{

                $subscription_data = '';

                $next_billing_date = @$subscription_user->cancel_date;

                $payment_methods = '';
                //$active_status = 'inactive';
                $active_status = 'You have no active subscriptions';

                $active_subscriptions = 'false';
            }

        }else{

                $subscription_data = '';

                $next_billing_date = '';

                $payment_methods = '';
                //$active_status = 'inactive';
                $active_status = 'You have no active subscriptions';

                $active_subscriptions = 'false';
            }

        if(@$subscription_user == ''){

            $subData = array(

                    "id" => null,
                    "subscription_type" => "",
                    "services"=> [],
                    "product_id" => null ,
                    "title" => "",
                    "sub_title" => "",
                    "amount" =>  null,
                    "per_year_amount" => null,
                    "description" => "",
                    "detail_description" => null,
                    "detail_page_message" => null,
                    "try_bottom_button_text" => "",
                    "start_date" => "",
                    "end_date" => "",
                    "status" => "",
                    "created_at" => "",
                    "updated_at" => "",
                    "deleted_at" => null
            );


            return response()->json([
                'status'=>true,
                'data' => @$subData,
                'active_data' => @$active_status,
                'active_subscriptions' => @$active_subscriptions,
                'next_billing_date' => @$next_billing_date,
                'payment_methods' => @$payment_methods,
                'message' => 'No data found.!',
            ], 200);

        }else{

            return response()->json([
                'status'=>true,
                'data' => @$subscription_data,
                'active_data' => @$active_status,
                'active_subscriptions' => @$active_subscriptions,
                'next_billing_date' => @$next_billing_date,
                'payment_methods' => @$payment_methods,
                'message' => 'Subscription data get successfully.',
            ], 200);
        }

    }
    /***
    *   Developed by: Dhruvish suthar
    *   Description: subscription details
    ***/
    public function subscription_details(Request $request){

        $validator = Validator::make($request->all(), [
              'subscription_id' => 'required',
        ]);
        
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $subscriptionlist = Subscription::where('id',$request->subscription_id)->first();

        $subscription_user = Subscription_user::where('user_id',$request->user()->id)->where('status','active')->first();

        $today_date = date("Y-m-d");


        //dd($today_date,$subscription_user->cancel_date);

        if(@$subscriptionlist->subscription_type == 'month'){

            $subscriptionlist->subscription_type = 'Monthly';

        }elseif(@$subscriptionlist->subscription_type == 'quarter'){

            $subscriptionlist->subscription_type = 'Quarterly';

        }elseif(@$subscriptionlist->subscription_type == 'annual'){

            $subscriptionlist->subscription_type = 'Annually';
        }

        if(@$subscriptionlist->start_date){

            @$subscriptionlist->start_date = date('M d, Y'); // start_date ma today ni date avse 
        }

        if(@$subscriptionlist->end_date){

            @$subscriptionlist->end_date = date('M d, Y',strtotime(@$subscriptionlist->end_date));
        }
  
        if($subscription_user){

            if($subscription_user->cancel_date >= $today_date){

               // $active_status = 'active';
               $active_status = 'You have active subscriptions';
            }else{

                //$active_status = 'inactive';
                $active_status = 'You have no active subscriptions';
            }
        }


        if($subscriptionlist == ''){

            return response()->json([
                'status'=>true,
                'data' => $subscriptionlist,
                'active_data' => @$active_status,
                'message' => 'No data found.!',
            ], 200);

        }else{

            return response()->json([
                'status'=>true,
                'data' => $subscriptionlist,
                'active_data' => @$active_status,
                'message' => 'Subscription data get successfully.',
            ], 200);
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: review rattings store
    ***/
    public function review_rattings_store(Request $request){

        $validator = Validator::make($request->all(), [
              'comment' => 'required',
              'rattings' => 'required',
        ]);
        
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $review_rattingsStore = Review_rattings_stores::create([
                'user_id' => @$request->user()->id,
                'comment' => @$request->comment,
                'rattings' => $request->rattings,
            ]);


        return response()->json([
                'status'=>true,
                'message' => 'Ratting store successfully.',
            ], 200);
    }
    
    /***
    *   Developed by: Dhruvish suthar
    *   Description: get profile detail of login user
    ***/
    public function getProfile(Request $request)
    {
        // dd($user = $request->user());
       // $userProfileData=$request->user()->makeHidden('date_of_birth')->first();

         $userProfileData = User::where('id',$request->user()->id)->first();

         $getNotifications_count = Notifications::where('user_id',$request->user()->id)->where('notify_read_unread','1')->orderBy('id', 'desc')->get();

        $NotificationsCount = @$getNotifications_count->count();

        $user_token =  $userProfileData->createToken('blessed_quest')->accessToken; 

        /// login type //////
        if($userProfileData->login_type == '1'){

            $loginSocial_type = 'apple';

        }elseif($userProfileData->login_type == '2'){

            $loginSocial_type = 'google';
        }else{

            $loginSocial_type = 'normal';
        }


        // if($userProfileData->default_prayer_id){

        //         $eventGet = Events::where('id',$userProfileData->default_prayer_id)->first();

        //         $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->where('voice_type',@$userProfileData->type_of_voice)->first();

        //         if(@$get_lib->mp3_file_name != '')
        //         {
        //             $ll['user_song'] = url('uploads/mp3')."/".@$get_lib->mp3_file_name;

        //         }else{

        //             $ll['user_song'] = "";
        //         }
        // }
        // $default_prayer = [
        //             'default_prayer_id' =>  @$userProfileData->default_prayer_id,
        //             'default_prayer_url' =>  @$ll['user_song'],
        // ];

        // if($userProfileData->default_prayer_id){

        //     $eventGet = Events::where('id',$userProfileData->default_prayer_id)->first();

        //     $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->get();

        //     $ll_song = array();
        //     foreach ($get_lib as $get_libvalue) {
                
        //         if(@$get_libvalue->mp3_file_name != '')
        //         {
        //             $ll['user_song'] = url('uploads/mp3')."/".@$get_libvalue->mp3_file_name;

        //             $ll_song[] = [
        //                 'default_prayer_id' =>  @$userProfileData->default_prayer_id,
        //                 'default_prayer_url' =>  @$ll['user_song'],
        //                 'voice_type'  =>  @$get_libvalue->voice_type,
        //             ];
        //         }
        //     }
        //     $default_data = [
        //         'default_id' => @$userProfileData->default_prayer_id,
        //         'prayer_list' => @$ll_song,
        //     ];
        // }else{
        //     $default_data = '';
        // }

        // if($userProfileData->default_prayer_id){

        //     $events_user = Librarys::select('id','name')->where('status','active')->orderBy('id', 'desc')->get();

        //     $eventData = array();
        //     foreach ($events_user as  $value) {

        //         $librarys_user = Events_user::select('event_id','event_name')->where('user_id',$userProfileData->id)->where('library_id',$value->id)->where('status','active')->first();

        //         $ll = $value;

        //             $librarys_audioget = Librarys_audio::where('voice_type',@$userProfileData->type_of_voice)->where('library_id',@$value->id)->where('status','active')->first();

        //             if(@$librarys_audioget->mp3_file_name != '')
        //             {
        //                 $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;

        //             }else{

        //                 $ll['user_song'] = '';
        //             }

        //             $ll['event_id'] = @$librarys_user->event_id;
        //             $ll['event_name'] = @$librarys_user->event_name;

        //         $eventData[] = $ll;
        //     }
        //     $default_data = @$eventData;
        // }else{
        //     $default_data = '';
        // }
        $default_data = Common::getEventUser($userProfileData->id,"user_id");
        // login type ///////////
        if($userProfileData){

            $getDefault_picture = Default_profile_picture::where('id',@$userProfileData->default_img_id)->first();

            if($userProfileData->default_img_id)
            {
                $userProfileData['default_img_id'] = @$getDefault_picture->id;
                $userProfileData['profile_picture'] = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;

            }elseif($userProfileData['profile_picture'] != ''){
                
                $userProfileData['profile_picture'] = url(config('settings.client_picture_folder'))."/".$userProfileData['profile_picture'];
            }

            return response()->json([
                'status'=>true,
               // 'user_status' => $userProfileData['status'],
                'message' => 'User Profile Data.',
                'data' => $this->setData($userProfileData),
                'token' => $user_token,
                'login_type' => $loginSocial_type,
                'notification_count' => @$NotificationsCount,
               // 'default_prayer' => @$default_prayer,
                'Events_data' => @$default_data,
            ], 200);
        }else{
            return response()->json([
                'status'=>true,
               // 'user_status' => 'block',
                'message' => 'User Not exists.',
                'data' => $this->setData($userProfileData),
                'login_type' => $loginSocial_type,
                'notification_count' => @$NotificationsCount,
                //'default_prayer' => @$default_prayer,
                'Events_data' => @$default_data,
            ], 200);
        }  
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: get dashboard detail of login user
    ***/
    public function updateProfile(Request $request)
    {
        // dd($request->all(),$request->bearerToken());
        $userProfileData=$request->user();
        // dd($userProfileData->email, $request->email);
        if($userProfileData->email!=$request->email)
        {
            $unique_validation='|unique:users';
        }
        else{
            $unique_validation="";
        }
        //name,email,dob,phone,postcode
        $validator = Validator::make($request->all(), [
            // 'first_name' => 'required|string',
            // 'last_name' => 'required|string',
            'name' => 'required|string',
          //  'phone' => 'digits:10',
            'language' => 'required',
            'type' => 'required',
            'email' => 'required|string|email'.$unique_validation.'',

        ]);

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        //////////////////// profile img code ///////////////////
                if($request->profile_picture)
                {  
                    $validator = Validator::make($request->all(), [
                        'profile_picture' => 'mimes:jpeg,jpg,png|required',
                        'type' => 'required',
                    ]);
                    
                    if($validator->fails())
                    {
                        return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
                    }
                    
                    $user = User::where('id',$request->user()->id)->first();

                    if($user->profile_picture)
                    {
                        $file = config('settings.client_picture_folder')."/".$user->profile_picture;
                        if(File::exists($file)) {
                            File::delete($file);
                        }
                    }
                    $fileName = "picture_".time().".".$request->profile_picture->getClientOriginalExtension();

                    $path = $request->profile_picture->move(config('settings.client_picture_folder'),$fileName);
                     
                    $user['profile_picture'] = $fileName; 

                    $user['default_img_id'] = ''; 

                    $user->update();
                }

                // $user['name'] = ($user->name) ? $user->name : $user->first_name.' '.$user->last_name;

                // $user['profile_picture'] = url(config('settings.client_picture_folder'))."/".$user->profile_picture;
        /////////////////////////////////////////////////////////

        $dob = NULL;
        if($request->date_of_birth)
        {
            $dob = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
        }
        
        $user = User::where('id',$request->user()->id)->update([
            'name' => $request->name,
           // 'email' => $request->email,
            'language' => $request->language,
            'phone_number' => $request->phone,
            'profile_status' => $request->profile_status,
            'default_img_id' => $request->default_img_id,
        ]);

        $userProfileData = User::where('id',$request->user()->id)->first();

        $userProfileData['name'] =  ($request->name) ? $request->name : $userProfileData->first_name.' '.$userProfileData->last_name;

        $device = UserDevice::where('user_id',$request->user()->id)->where('type',$request->type)->first();

        $userProfileData['device_id'] = ($device) ? $device->device_id : ' ';
        $userProfileData['device_token'] = ($device) ? $device->device_token : ' ';

        // if(($userProfileData->profile_picture != '') && ($request->default_img_id == '') )
        // {
        //     $userProfileData->profile_picture = url(config('settings.client_picture_folder'))."/".$userProfileData->profile_picture;
        // }else{

        //     if($request->default_img_id){

        //             $getDefault_picture = Default_profile_picture::where('id',@$request->default_img_id)->first();
        //             $userProfileData->profile_picture = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;
        //     }else{

        //         $userProfileData->profile_picture = url('uploads/default_profile_picture')."/Icon_logo.png";
        //     }
        // }

        if($request->default_img_id != '') 
        {
             $getDefault_picture = Default_profile_picture::where('id',@$request->default_img_id)->first();
                    $userProfileData->profile_picture = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;

        }elseif($userProfileData->profile_picture != ''){

            $userProfileData->profile_picture = url(config('settings.client_picture_folder'))."/".$userProfileData->profile_picture;
        }

      //  Common::logActivity($userProfileData, $request->user() , $userProfileData->toArray(),"Profile Updated By Client.");

      //  return $this->sendResponse($userProfileData, 'Profile updated successfully.');


            if($userProfileData->login_type == '1'){

                $loginSocial_type = 'apple';

            }elseif($userProfileData->login_type == '2'){

                $loginSocial_type = 'google';
            }else{

                $loginSocial_type = 'normal';
            }

        $user_token =  $userProfileData->createToken('blessed_quest')->accessToken;

        $getNotifications_count = Notifications::where('user_id',$userProfileData->id)->where('notify_read_unread','1')->orderBy('id', 'desc')->get();

        $NotificationsCount = @$getNotifications_count->count();


        // if($userProfileData->default_prayer_id){
        //         $eventGet = Events::where('id',$userProfileData->default_prayer_id)->first();
        //         $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->where('voice_type',@$userProfileData->type_of_voice)->first();

        //         if(@$get_lib->mp3_file_name != '')
        //         {
        //             $ll['user_song'] = url('uploads/mp3')."/".@$get_lib->mp3_file_name;
        //         }else{
        //             $ll['user_song'] = "";
        //         }
        // }
        // $default_prayer = [
        //             'default_prayer_id' =>  @$userProfileData->default_prayer_id,
        //             'default_prayer_url' =>  @$ll['user_song'],
        // ];

        // if($userProfileData->default_prayer_id){

        //         $events_user = Librarys::select('id','name')->where('status','active')->orderBy('id', 'desc')->get();

        //         $eventData = array();
        //         foreach ($events_user as  $value) {

        //             $librarys_user = Events_user::select('event_id','event_name')->where('user_id',$userProfileData->id)->where('library_id',$value->id)->where('status','active')->first();

        //             $ll = $value;

        //                 $librarys_audioget = Librarys_audio::where('voice_type',@$userProfileData->type_of_voice)->where('library_id',@$value->id)->where('status','active')->first();

        //                 if(@$librarys_audioget->mp3_file_name != '')
        //                 {
        //                     $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;

        //                 }else{

        //                     $ll['user_song'] = '';
        //                 }

        //                 $ll['event_id'] = @$librarys_user->event_id;
        //                 $ll['event_name'] = @$librarys_user->event_name;

        //             $eventData[] = $ll;
        //         }
        //         // $default_data = [

        //         //     //'default_id' => @$user->default_prayer_id,
        //         //     'Events_data' => @$eventData,
        //         // ];
        //         $default_data = @$eventData;

        //         }else{
        //             $default_data = '';
        //         }

        $default_data = Common::getEventUser($userProfileData->id,"user_id");

         return response()->json([
                    'status'=>true,
                    'message' => 'Profile updated successfully.',
                    'data' => $userProfileData,
                    'token' => $user_token,
                    'login_type' => $loginSocial_type,
                    'notification_count' => @$NotificationsCount,
                   // 'default_prayer' => @$default_prayer,
                    'Events_data' => @$default_data,
                   // 'token' => ,
                ], 200);
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: Update profile picture of logged in user in database
    ***/
    public function uploadprofilepicture(Request $request)
    {
        $user = User::where('id',$request->user()->id)->first();
        
        $validator = Validator::make($request->all(), [
            'profile_picture' => 'mimes:jpeg,jpg,png|required',
            'type' => 'required',
        ]);
        
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        if($request->profile_picture)
        {  
            if($user->profile_picture)
            {
                $file = config('settings.client_picture_folder')."/".$user->profile_picture;
                if(File::exists($file)) {
                    File::delete($file);
                }
            }
            $fileName = "picture_".time().".".$request->profile_picture->getClientOriginalExtension();

            $path = $request->profile_picture->move(config('settings.client_picture_folder'),$fileName);
             
            $user['profile_picture'] = $fileName; 
        }

        $user->update();
        
        $user['name'] = ($user->name) ? $user->name : $user->first_name.' '.$user->last_name;

        $user['profile_picture'] = url(config('settings.client_picture_folder'))."/".$user->profile_picture;

        $device = UserDevice::where('user_id',$request->user()->id)->where('type',$request->type)->first();

        $user['device_id'] = ($device) ? $device->device_id : ' ';
        $user['device_token'] = ($device) ? $device->device_token : ' ';

        return $this->sendResponse($user, 'Profile picture updated successfully.',200);

    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: Get Notifications
    ***/
    public function Getnotifications(Request $request)
    {
        $rules= [
            'page_Id' => 'required', 
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $getNotifications = Notifications::where('user_id',$request->user()->id)->orderBy('id', 'desc')->paginate(10, ['*'], 'page', $request->page_Id);

        $getNotifications_count = Notifications::where('user_id',$request->user()->id)->where('notify_read_unread','1')->orderBy('id', 'desc')->get();

        $NotificationsCount = $getNotifications_count->count();

        foreach ($getNotifications as $getNotificationsv) {
            // Append the 'tags' array to each user (can be any other array or data)

            if($getNotificationsv->notification_img != ''){

                $getNotificationsv->notification_img = url(config('settings.notifications_img'))."/".$getNotificationsv->notification_img;

            }else{

                $getNotificationsv->notification_img = url('uploads/default_profile_picture')."/Icon_logo.png";
            }
        }


        foreach ($getNotifications_count as $value) {
            
            $notification = Notifications::where('id',$value->id)->first();
            $notification['notify_read_unread'] = '0'; 
            $notification->update();
        }

        //dd(count($getNotifications),$request->user()->id);
        if(count($getNotifications) > 0){

            return response()->json([
                'status'=>true,
                'message' => 'Notification details get !.',
                'data' => $getNotifications,
                'notification_count' => @$NotificationsCount,
            ], 200);

        }else{

            return response()->json([
                'status'=>true,
                'message' => 'No data found.!',
                'data' => $getNotifications,
                'notification_count' => @$NotificationsCount,
            ], 200);
        }
    }


    public function user_noti_settings_update_list(Request $request){

        $get_notification = User_notifications_settings::where('user_id',@$request->user()->id)->first();

            return response()->json([
                'status'=>true,
                'message' => 'Get Successfully.!',
                'data' => $get_notification,
            ], 200);
    }


    public function notificationsRead(Request $request)
    {
        $rules= [
            'id' => 'required', 
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $notification = Notifications::where('id',$request->id)->first();
        $notification['notify_read_unread'] = '0'; 
        $notification->update();

        //dd($getNotifications);
         return response()->json([
            'status'=>true,
            'message' => 'Notification read successfully !.',
        ], 200);
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: Update email of login user
    ***/
    public function updateEmail(Request $request)
    {
        $rules= [
            'newEmail' => 'required',    
            'type' => 'required',   

        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        // check duplicate email
        $email = $request->newEmail;
        $client = User::where('email','=',$email)->first();

        if($client){
            return $this->sendError(__('Email address already exists.'),200);
        }

        try
        {
            if(!$client)
            {
                $data['email'] = $email;
                $data['user'] = 'client';
                $data['userId'] = $request->user()->id;

                /*** Send verification email to client ***/
                \Mail::to($email)->send(new \App\Mail\VerifyNewEmail($data));             

                /*** Send email update notification to client ***/
                $user = User::find($request->user()->id);
                $user->sendUpdateEmailNotification($user);                 

                /** Log activity **/
                Common::logActivity($user, $request->user() , $user->toArray(),"Email Updated By Client.");

                return response()->json([
                    'status'=>true,
                    'user_status' => $user->status,
                    'message' => 'Update Email Verification Link Sent Successfully.',
                    // 'user_data' => $this->setData($userProfileData->toArray()),
                ], 200);
            }
        }
        catch (\Exception $e) {
            echo $e->getMessage(); die;
            return response()->json([
                'status' => false,
                'message' => __('messages.serverError'),
            ], 200);
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: blessed journey store
    ***/
    public function blessed_journey_store(Request $request)
    {
        // $rules= [
        //     'latitude' => 'required',    
        //     'longitude' => 'required',   
        //     'description' => 'required',   
        // ];

        // $validator = Validator::make($request->all(),$rules);
        // if($validator->fails())
        // {
        //     return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        // }

        // if($request->img)
        // {  
        //     $fileName = "location_".time().".".$request->img->getClientOriginalExtension();

        //     $path = $request->img->move('storage/location_img',$fileName);
             
        //      $store_notification = Blessed_location_details::create([
        //         'user_id' => @$request->user()->id,
        //         'latitude' => @$request->latitude,
        //         'img' => @$fileName,
        //         'description' => $request->description,
        //         'longitude' => $request->longitude,
        //     ]);
        // }else{

        //     $store_notification = Blessed_location_details::create([
        //         'user_id' => @$request->user()->id,
        //         'latitude' => @$request->latitude,
        //         'description' => $request->description,
        //         'longitude' => $request->longitude,
        //     ]);
        // }

            //14-11-2024 start
            // $rules= [
            //     'name' => 'required',    
            //     'location' => 'required',   
            // ];

            // $validator = Validator::make($request->all(),$rules);
            // if($validator->fails())
            // {
            //     return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
            // }

            //   $store_notification = Blessed_location_details::create([
            //     'name' => @$request->name,
            //     'user_id' => @$request->user()->id,
            //   ]);

            // if($request->location){
            //     $location_deta =  json_decode($request->location);
            //      foreach($location_deta as $locationimgs){
            //         $store_list_lo = Blessed_location_list::create([
            //             'user_id' => @$request->user()->id,
            //             'blessed_location_details_id' => @$store_notification->id,
            //             'description' => @$locationimgs->description,
            //             'latitude' =>  @$locationimgs->latitude,
            //             'longitude' =>  @$locationimgs->longitude,
            //         ]);

            //         foreach ($locationimgs->images as $valueImg) {

            //             $store_imgs = Blessed_location_imgs::create([
            //             'user_id' => @$request->user()->id,
            //             'image_name' => @$valueImg,
            //             'blessed_location_id' => @$store_notification->id,
            //             'blessed_location_list_id' => @$store_list_lo->id,
            //             ]);
            //         }
            //      }
            //  }
            //14-11-2024 end

            if(@$request->id){

                $rules= [
                    'name' => 'required',    
                    'location' => 'required',   
                ];

                $validator = Validator::make($request->all(),$rules);
                if($validator->fails())
                {
                    return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
                }

                  // $store_location_details = Blessed_location_details::create([
                  //   'name' => @$request->name,
                  //   'user_id' => @$request->user()->id,
                  // ]);

                    $store_location_details = Blessed_location_details::where('id',$request->id)->first();
                    $store_location_details['name'] = @$request->name;
                    $store_location_details->update();

                  // source
                if($request->source){
                    $source_deta =  json_decode($request->source);
                   // dd($request->destination,$destination_deta);
                        // $source_data_store = Source_details::create([
                        //     'user_id' => @$request->user()->id,
                        //     'name' => @$source_deta->name,
                        //     'blessed_location_details_id' => @$store_location_details->id,
                        //     'location' =>  @$source_deta->location,
                        // ]);

                        $source_data_store = Source_details::where('blessed_location_details_id',$request->id)->first();
                        $source_data_store['name'] = @$source_deta->name;
                        $source_data_store['location'] = @$source_deta->location;
                        $source_data_store->update();

                         $delete_sou = Source_details_img::where('source_details_id',$source_data_store->id)->delete();

                        foreach ($source_deta->images as $sourceImg) {

                            /// download img ///////////

                               if($sourceImg){

                                  $imagePathsourceImg = Common::imgURL_get($sourceImg,"Source_details_img");

                                }else{
                                   $imagePathsourceImg = '';
                                }
                            /// download img ///////////

                            $sourcestore_imgs = Source_details_img::create([
                            'user_id' => @$request->user()->id,
                            'source_details_id' => @$source_data_store->id,
                            'image' => @$imagePathsourceImg,

                            ]);
                        }
                }

                // destination
                if($request->destination){
                    $destination_deta =  json_decode($request->destination);
                   // dd($request->destination,$destination_deta);

                        // $destination_data = Destination_details::create([
                        //     'user_id' => @$request->user()->id,
                        //     'name' => @$destination_deta->name,
                        //     'blessed_location_details_id' => @$store_location_details->id,
                        //     'location' =>  @$destination_deta->location,
                        // ]);

                        $destination_data = Destination_details::where('blessed_location_details_id',$request->id)->first();
                        $destination_data['name'] = @$destination_deta->name;
                        $destination_data['location'] = @$destination_deta->location;
                        $destination_data->update();

                        $delete_des = Destination_details_img::where('destination_details_id',$destination_data->id)->delete();

                        foreach ($destination_deta->images as $destinationImg) {

                             /// download img ///////////

                               if($destinationImg){

                                $imagePathDestination = Common::imgURL_get($destinationImg,"Destination_details_img");

                                }else{
                                   $imagePathDestination = '';
                                }
                            /// download img ///////////

                            $store_imgs = Destination_details_img::create([
                            'user_id' => @$request->user()->id,
                            'destination_details_id' => @$destination_data->id,
                            'image' => @$imagePathDestination,

                            ]);
                        }
                }

                if($request->location){

                    $delete_Blessed = Blessed_location_imgs::where('blessed_location_id',$store_location_details->id)->delete();

                    $delete_Blessed_lis = Blessed_location_list::where('blessed_location_details_id',$store_location_details->id)->delete();

                    $location_deta =  json_decode($request->location);
                     foreach($location_deta as $locationimgs){

                        $store_list_lo = Blessed_location_list::create([
                            'user_id' => @$request->user()->id,
                            'blessed_location_details_id' => @$store_location_details->id,
                            'description' => @$locationimgs->description,
                            'latitude' =>  @$locationimgs->latitude,
                            'longitude' =>  @$locationimgs->longitude,
                            'mile' =>  @$locationimgs->mile,
                            'name' =>  @$locationimgs->name,
                        ]);

                        foreach ($locationimgs->images as $valueImg) {

                            /// download img ///////////

                           if($valueImg){

                              $imagePath = Common::imgURL_get($valueImg,"Blessed_location_imgs");

                            }else{
                               $imagePath = '';
                            }
                            /// download img ///////////

                            $store_imgs = Blessed_location_imgs::create([
                            'user_id' => @$request->user()->id,
                            'image_name' => @$imagePath,
                            'blessed_location_id' => @$store_location_details->id,
                            'blessed_location_list_id' => @$store_list_lo->id,
                            ]);
                        }
                     }
                }

                return response()->json([
                    'id' => @$store_location_details->id,
                    'status'=>true,
                    'message' => 'Updated successfully.',
                ], 200);

            }else{

                $rules= [
                    'name' => 'required',    
                    'location' => 'required',   
                ];

                $validator = Validator::make($request->all(),$rules);
                if($validator->fails())
                {
                    return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
                }

                  $store_location_details = Blessed_location_details::create([
                    'name' => @$request->name,
                    'user_id' => @$request->user()->id,
                  ]);

                  // source
                if($request->source){
                    $source_deta =  json_decode($request->source);
                   // dd($request->destination,$destination_deta);
                        $source_data_store = Source_details::create([
                            'user_id' => @$request->user()->id,
                            'name' => @$source_deta->name,
                            'blessed_location_details_id' => @$store_location_details->id,
                            'location' =>  @$source_deta->location,
                        ]);

                        foreach ($source_deta->images as $sourceImg) {

                            /// download img ///////////

                            // $sourceImgg = "AdDdOWqy8fMc9ZQsJwWIQrHwAoQx7Fd3uIO-7lsl0j8Jj9qFlgmJIeN5kbbSXAYrHeed38tBWTjz1d_sybZ1wrWbgY-nQQd_wReUhZaHGp2L1O8OL-PI8DIpKNEaDiPRuGjSlTyoBhbCunV8Nn7I3l7q2C1jO-PCgtj6B_rXUfoRHuUVII1";
                               if($sourceImg){
                                  
                                  $imagePathsourceImg = Common::imgURL_get($sourceImg,"Source_details_img");

                                }else{
                                   $imagePathsourceImg = '';
                                }
                            /// download img ///////////

                            $sourcestore_imgs = Source_details_img::create([
                            'user_id' => @$request->user()->id,
                            'source_details_id' => @$source_data_store->id,
                            'image' => @$imagePathsourceImg,

                            ]);
                        }
                }

                // destination
                if($request->destination){
                    $destination_deta =  json_decode($request->destination);
                   // dd($request->destination,$destination_deta);
                        $destination_data = Destination_details::create([
                            'user_id' => @$request->user()->id,
                            'name' => @$destination_deta->name,
                            'blessed_location_details_id' => @$store_location_details->id,
                            'location' =>  @$destination_deta->location,
                        ]);

                        foreach ($destination_deta->images as $destinationImg) {

                            /// download img ///////////

                            //$imageUrl = 'https://lh3.googleusercontent.com/places/ANXAkqFus0lwdm2m5E57jIWBdObEz9HmjjvVnfDMwFwOcmu3Cgv7YU9ojBaPvQ-t4B5h9UOI9r0hemAwz2N28nByadKv6BcWxbu8tCc=s1600-w250';
                               if($destinationImg){

                                $imagePathDestination = Common::imgURL_get($destinationImg,"Destination_details_img");

                                }else{
                                   $imagePathDestination = '';
                                }
                            /// download img ///////////

                            $store_imgs = Destination_details_img::create([
                            'user_id' => @$request->user()->id,
                            'destination_details_id' => @$destination_data->id,
                            'image' => @$imagePathDestination,

                            ]);
                        }
                }

                if($request->location){
                    $location_deta =  json_decode($request->location);
                     foreach($location_deta as $locationimgs){
                        $store_list_lo = Blessed_location_list::create([
                            'user_id' => @$request->user()->id,
                            'blessed_location_details_id' => @$store_location_details->id,
                            'description' => @$locationimgs->description,
                            'latitude' =>  @$locationimgs->latitude,
                            'longitude' =>  @$locationimgs->longitude,
                            'mile' =>  @$locationimgs->mile,
                            'name' =>  @$locationimgs->name,
                        ]);

                        foreach ($locationimgs->images as $valueImg) {

                            /// download img ///////////

                            //$imageUrl = 'https://lh3.googleusercontent.com/places/ANXAkqFus0lwdm2m5E57jIWBdObEz9HmjjvVnfDMwFwOcmu3Cgv7YU9ojBaPvQ-t4B5h9UOI9r0hemAwz2N28nByadKv6BcWxbu8tCc=s1600-w250';
                           if($valueImg){

                              $imagePath = Common::imgURL_get($valueImg,"Blessed_location_imgs");

                            }else{
                               $imagePath = '';
                            }
                            /// download img ///////////

                            $store_imgs = Blessed_location_imgs::create([
                            'user_id' => @$request->user()->id,
                            'image_name' => @$imagePath,
                            'blessed_location_id' => @$store_location_details->id,
                            'blessed_location_list_id' => @$store_list_lo->id,
                            ]);
                        }
                     }
                }

                return response()->json([
                    'id' => @$store_location_details->id,
                    'status'=>true,
                    'message' => 'Store successfully.',
                ], 200);
            }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: blessed journey store backend
    ***/
    public function blessed_journey_store_backend(Request $request)
    {
                $rules= [
                    'name' => 'required',    
                    'location' => 'required',   
                ];

                $validator = Validator::make($request->all(),$rules);
                if($validator->fails())
                {
                    return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
                }

                  $store_location_details = Blessed_location_details::create([
                    'name' => @$request->name,
                    'user_id' => @$request->user()->id,
                  ]);

                  // source
                if($request->source){
                    $source_deta =  json_decode($request->source);
    
                        $source_data_store = Source_details::create([
                            'user_id' => @$request->user()->id,
                            'name' => @$source_deta->name,
                            'blessed_location_details_id' => @$store_location_details->id,
                            'location' =>  @$source_deta->location,
                        ]);

                        ProcessImage::dispatch($source_deta->images,$request->user()->id,$store_location_details);

                        // foreach ($source_deta->images as $sourceImg) {

                        //        if($sourceImg){

                        //           //$imagePathsourceImg = Common::imgURL_get($sourceImg,"Source_details_img");

                        //            ProcessImage::dispatch($sourceImg);

                        //         }else{
                        //            $imagePathsourceImg = '';
            
                        //         }
                        //     /// download img ///////////

                        //     $sourcestore_imgs = Source_details_img::create([
                        //     'user_id' => @$request->user()->id,
                        //     'source_details_id' => @$source_data_store->id,
                        //     'image' => @$imagePathsourceImg,
                        //     ]);
                        // }

                        //$destination_deta =  json_decode($request->destination);
                        //$location_deta =  json_decode($request->location);
                       // ProcessImage::dispatch($destination_deta->images,$request->user()->id);
                       // ProcessImagemulty::dispatch($location_deta,$request->user()->id);
                }
                // destination
                if($request->destination){
                    $destination_deta =  json_decode($request->destination);
                   // dd($request->destination,$destination_deta);
                        $destination_data = Destination_details::create([
                            'user_id' => @$request->user()->id,
                            'name' => @$destination_deta->name,
                            'blessed_location_details_id' => @$store_location_details->id,
                            'location' =>  @$destination_deta->location,
                        ]);

                        // foreach ($destination_deta->images as $destinationImg) {

                        //     /// download img ///////////
                        //        if($destinationImg){

                        //         $imagePathDestination = Common::imgURL_get($destinationImg,"Destination_details_img");

                        //         }else{
                        //            $imagePathDestination = '';
                        //         }
                        //     /// download img ///////////

                        //     $store_imgs = Destination_details_img::create([
                        //     'user_id' => @$request->user()->id,
                        //     'destination_details_id' => @$destination_data->id,
                        //     'image' => @$imagePathDestination,

                        //     ]);
                        // }

                        DestinationImage::dispatch($destination_deta->images,$request->user()->id,$destination_data);
                }

                if($request->location){
                    $location_deta =  json_decode($request->location);
                     // foreach($location_deta as $locationimgs){
                     //    $store_list_lo = Blessed_location_list::create([
                     //        'user_id' => @$request->user()->id,
                     //        'blessed_location_details_id' => @$store_location_details->id,
                     //        'description' => @$locationimgs->description,
                     //        'latitude' =>  @$locationimgs->latitude,
                     //        'longitude' =>  @$locationimgs->longitude,
                     //        'mile' =>  @$locationimgs->mile,
                     //        'name' =>  @$locationimgs->name,
                     //    ]);

                     //    foreach ($locationimgs->images as $valueImg) {

                     //        /// download img ///////////
                     //       if($valueImg){

                     //          $imagePath = Common::imgURL_get($valueImg,"Blessed_location_imgs");

                     //        }else{
                     //           $imagePath = '';
                     //        }
                     //        /// download img ///////////

                     //        $store_imgs = Blessed_location_imgs::create([
                     //        'user_id' => @$request->user()->id,
                     //        'image_name' => @$imagePath,
                     //        'blessed_location_id' => @$store_location_details->id,
                     //        'blessed_location_list_id' => @$store_list_lo->id,
                     //        ]);
                     //    }

                        ProcessImagemulty::dispatch($location_deta,$request->user()->id,$store_location_details->id);
                    //  }  
                }

                return response()->json([
                    'id' => @$store_location_details->id,
                    'status'=>true,
                    'message' => 'Store successfully.',
                ], 200);
            
    }




    /***
    *   Developed by: Dhruvish suthar
    *   Description: list blessed
    ***/
    public function blessed_journey_list(Request $request)
    {
        $rules= [
            'page_Id' => 'required', 
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $store_notification = Blessed_location_details::where('user_id',@$request->user()->id)->orderBy('id', 'desc')->orderBy('id', 'desc')->paginate(10, ['*'], 'page', $request->page_Id);

        $userData = array();
        foreach ($store_notification as  $value) {

            $ll = $value;

            if($value)
            {
               // $ll['location'] = Blessed_location_list::with('location_images')->select('id','description','latitude','longitude','name','mile')->where('blessed_location_details_id',$value->id)->get();

                $locationGetval = Blessed_location_list::with('location_images')->select('id','description','latitude','longitude','name','mile')->where('blessed_location_details_id',$value->id)->get();
                //dd($ll['location']);
               // $ll['list_imgs'] = Blessed_location_imgs::where('blessed_location_id',$value->id)->get();
                $location = array();
                foreach ($locationGetval as $valuelocationGetval) {
                        
                       // dd($valuelocationGetval['location_images']);
                        if(count($valuelocationGetval['location_images']) > 0)
                        {
                            $locationImg = array();
                            foreach ($valuelocationGetval['location_images'] as $valuelocation_images) {
                                
                                if($valuelocation_images->image_name){

                                    $img_pathlocation = url('uploads/Blessed_location_imgs')."/".$valuelocation_images->image_name;

                                }else{

                                    $img_pathlocation = '';
                                }
                                $locationImg[] =[

                                    "image_name"=> @$img_pathlocation,
                                    "blessed_location_id"=> @$valuelocation_images->blessed_location_id,
                                ];
                            }
                        }

                  $location[] =
                    [
                        "id" => @$valuelocationGetval->id,
                        "description" => @$valuelocationGetval->description,
                        "latitude"=>  @$valuelocationGetval->latitude,
                        "longitude" => @$valuelocationGetval->longitude,
                        "name" => @$valuelocationGetval->name,
                        "mile" => @$valuelocationGetval->mile,
                        "location_images" => @$locationImg,
                    ];
                }


                $ll['location'] = $location;
            }else{
                $ll['location'] = '';

               // $ll['list_imgs'] = '';
            }

            // if(count($ll['img']) > 0){

            //     $valuelocationImgarray = array();
            //     foreach ($ll['img'] as $valuelocationImg) {


            //         if(count($ll['img']) > 0){

            //             $locationlist_imgs = array();
            //             foreach ($ll['list_imgs'] as $valuelocationlist_imgs) {

            //                 $locationlist_imgs[] = $valuelocationlist_imgs->image_name;
            //             }

            //         }

                    
            //         $valuelocationImgarray[] = array(
            //                     'description' => @$valuelocationImg->description,
            //                     'images' => @$locationlist_imgs,
            //                     'latitude' => @$valuelocationImg->latitude,
            //                     'longitude' => @$valuelocationImg->longitude,
            //         );
            //     }

            // }
            // $sourcef_details_data =  json_encode(@$valuelocationImgarray);
            //dd($sourcef_details_data);

            ///////  source    start  ////////
                $source_data = Source_details::with('source_images')->select('id','name','location')->where('blessed_location_details_id',$value->id)->first();
                $sourcestore_imgs = Source_details_img::select('image')->where('source_details_id',@$source_data->id)->get();
                
                $source_img =array();
                foreach (@$sourcestore_imgs as $valuesource_images){

                    if($valuesource_images->image){

                        if($valuesource_images->image){
                            $img_pathsource = url('uploads/Source_details_img')."/".$valuesource_images->image;
                        }else{

                            $img_pathsource = '';
                        }

                        $source_img[] = [
                            "destination_details_id" => @$img_pathsource->source_details_id,
                             "image" => @$img_pathsource,
                        ];
                    }
                }

                    $destination_dataval = array(

                        "id" => @$source_data->id,
                        "name" => @$source_data->name,
                        "location" => @$source_data->location,
                        "blessed_location_details_id" => @$source_data->blessed_location_details_id,
                        "destination_images" => @$source_img,
                    );

                $ll['source_data'] = @$destination_dataval; //images
                //$ll['sourcestore_imgs'] = @$sourcestore_imgs;

                //dd($source_data->source_details_data);
                // if(@$source_data){

                //     if(count($sourcestore_imgs) > 0){

                //         $val = array();
                //         foreach ($sourcestore_imgs as $sourcestorevalue) {
                            
                //             $val[] = $sourcestorevalue->images;
                //         }
                //     }

                //    $array_json =[

                //             'name'=> @$source_data->name,
                //             'location'=> @$source_data->location,
                //             'images'=> @$val,
                //    ];

                //     $source_details_data =  json_encode(@$array_json);

                // }else{

                //     $source_details_data = '';
                // }

             
            ////// source   end ////////

            ///////  destination    start  ////////
                $destination_data = Destination_details::with('destination_images')->select('id','name','location','blessed_location_details_id')->where('blessed_location_details_id',$value->id)->first();
               
                 $destination_imgs = Destination_details_img::where('destination_details_id',@$destination_data->id)->get();
                $destination_img =array();
                foreach (@$destination_imgs as $valuedestination_images){

                    if($valuedestination_images->image){

                        if($valuedestination_images->image){

                            $img_pathdestination = url('uploads/Destination_details_img')."/".$valuedestination_images->image;
                        }else{

                            $img_pathdestination = '';
                        }

                        $destination_img[] = [
                            "destination_details_id" => @$valuedestination_data->destination_details_id,
                             "image" => @$img_pathdestination,
                        ];
                    }
                }

                $destination_dataval = array(

                        "id" => @$destination_data->id,
                        "name" => @$destination_data->name,
                        "location" => @$destination_data->location,
                        "blessed_location_details_id" => @$destination_data->blessed_location_details_id,
                        "destination_images" => @$destination_img,
                    );

                $ll['destination_data'] = @$destination_dataval;
               // $ll['destinationstore_imgs'] = @$destination_imgs;

                // if(@$destination_data){

                //     if(count($destination_imgs) > 0){

                //         $valdestination = array();
                //         foreach ($destination_imgs as $destinationvalue) {
                            
                //             $valdestination[] = $destinationvalue->images;
                //         }
                //     }

                //    $array_jsondestination =[

                //             'name'=> @$destination_data->name,
                //             'location'=> @$destination_data->location,
                //             'images'=> @$valdestination,
                //    ];

                //     $destination_details_data =  json_encode(@$array_jsondestination);

                // }else{

                //     $destination_details_data = '';
                // }


            ////// destination   end ////////
           
            $userData[] = $ll;

              // $userData[] = [

              //           'name'=> @$value->name,
              //           'source' => @$source_details_data,
              //           'destination' => @$destination_details_data,
              //           'location' => @$sourcef_details_data,
              // ];

        }

        $pagination_data = array(

            'current_page' => @$store_notification->currentPage(),
            'last_page' => @$store_notification->lastPage(),
            'per_page'=> @$store_notification->perPage(),
            'total'=> @$store_notification->total(),
            'firstItem'=> @$store_notification->firstItem(),
            'lastItem'=> @$store_notification->lastItem(),
            'hasMorePages'=> @$store_notification->hasMorePages(),
        );

        if(count($userData) == 0){

            return response()->json([
                'status'=>true,
                'message' => 'No data found
                .',
                'user_data' => $userData,
                'pagination_data' => $pagination_data,
                //'source_data' => $source_data,
            ], 200);

        }else{

            return response()->json([
                'status'=>true,
                'message' => 'Data get successfully.',
                'user_data' => $userData,
                'pagination_data' => $pagination_data,
                //'source_data' => $source_data,
            ], 200);
        }
    }



    /***
    *   Developed by: Dhruvish suthar
    *   Description: remove location
    ***/
    public function blessed_remove(Request $request)
    {
        $rules= [
            'id' => 'required',      
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $store_notification = Blessed_location_details::where('user_id',@$request->user()->id)->where('id',$request->id)->first();

        if($store_notification){

            if(file_exists(public_path('storage/location_img')."/".$store_notification->img))
            {
                $file = public_path('storage/location_img')."/".$store_notification->img;
                File::delete($file);
            }

            $store_notification->delete();
             return response()->json([
                    'status'=>true,
                    'message' => 'Deleted successfully.',
                ], 200);

        }else{

             return response()->json([
                    'status'=>true,
                    'message' => 'Data not found.',
                ], 200);

        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: User notifications settings
    ***/
    public function user_noti_settings_update(Request $request)
    {
        
       $find_data = User_notifications_settings::where('user_id',@$request->user()->id)->first();

        if($find_data){

            $input['all_push_notifications'] = @$request->all_push_notifications;
            $input['traffic_route_updates'] = $request->traffic_route_updates;
            $input['receive_recommended_content'] = $request->receive_recommended_content;
            $input['reminders_credits_expire'] = $request->reminders_credits_expire;
            $input['reminders_sub_autorenews'] = $request->reminders_sub_autorenews;
            $input['notify_updates_changes_account'] = $request->notify_updates_changes_account;
            $input['notify_me_sub_and_sub_renewal_errors'] = $request->notify_me_sub_and_sub_renewal_errors;
            $input['email_notifications'] = $request->email_notifications;
            $input['receive_reminders_days_before_credits_expire_email'] = $request->receive_reminders_days_before_credits_expire_email;
            $input['reminders_sub_autorenews_email'] = $request->reminders_sub_autorenews_email;
            $input['notify_updates_changes_account_email'] = $request->notify_updates_changes_account_email;
            $input['notify_me_sub_and_sub_renewal_errors_email'] = $request->notify_me_sub_and_sub_renewal_errors_email;

            $find_data->update($input);

            return response()->json([
                'status'=>true,
                'message' => 'Updated Successfully.!',
                'data' => $find_data,
            ], 200);

        }else{

            $store_notification = User_notifications_settings::Create([
                'user_id' => @$request->user()->id,
                'all_push_notifications' => @$request->all_push_notifications,
                'traffic_route_updates' => $request->traffic_route_updates,
                'receive_recommended_content'  => $request->receive_recommended_content,
                'reminders_credits_expire'  => $request->reminders_credits_expire,
                'reminders_sub_autorenews'  => $request->reminders_sub_autorenews,
                'notify_updates_changes_account'  => $request->notify_updates_changes_account,
                'notify_me_sub_and_sub_renewal_errors'  => $request->notify_me_sub_and_sub_renewal_errors,
                'email_notifications'  => $request->email_notifications,
                'receive_reminders_days_before_credits_expire_email'  => $request->receive_reminders_days_before_credits_expire_email,
                'reminders_sub_autorenews_email'  => $request->reminders_sub_autorenews_email,
                'notify_updates_changes_account_email'  => $request->notify_updates_changes_account_email,
                'notify_me_sub_and_sub_renewal_errors_email'  => $request->notify_me_sub_and_sub_renewal_errors_email,
            ]);

            return response()->json([
                'status'=>true,
                'message' => 'Updated Successfully.!',
                'data' => $store_notification,
            ], 200);
        }
            
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: Store location
    ***/
    public function location_add(Request $request)
    {
        $rules= [
            'name'  => 'required',
            'latitude' => 'required',    
            'longitude' => 'required',   
            //'description' => 'required',   
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();

        if($request->id){

            $store_notification = User_location_details::where('id',$request->id)->first();

            //dd($store_notification);
            $store_notification['name'] = $request->name; 
            $store_notification['latitude'] = $request->latitude; 
            $store_notification['longitude'] = $request->longitude; 
            $store_notification['description'] = $request->description; 
            $store_notification['mile'] = $request->mile; 
            $store_notification->update();

            if($request->img){

                $arrayDataimg = explode(',', $request->img);

                if($request->img){

                        $deleteData = User_location_img::where('user_location_id',$request->id)->delete();
                }
                 
                foreach($arrayDataimg as $valueImgs){

                    /// download img ///////////
                        if($valueImgs){

                          $imagePath = Common::imgURL_get($valueImgs,"user_location_imgs");

                        }else{
                           $imagePath = '';
                        }
                    /// download img ///////////

                        $store_imgs = User_location_img::Create(
                            [
                            'user_id' => @$userProfileData['id'],
                            'user_location_id' => @$store_notification->id,
                            'img' => @$imagePath,
                            ]
                        );
                }
            }

            return response()->json([
                'status'=>true,
                'message' => 'Update successfully.',
                'data' => @$store_notification,
                'imgs' => @$store_imgs,
            ], 200);

        }else{
             
                $store_notification = User_location_details::Create(
                    [
                    'user_id' => @$userProfileData['id'],
                    'name' => @$request->name,
                    'latitude' => @$request->latitude,
                    'longitude' => $request->longitude,
                     //   'img' => @$request->img,
                        'description' => $request->description,
                        'mile' => $request->mile,
                    ]
                );

                if($request->img){


                    $arrayDataimg = explode(',', $request->img);

                    if($request->id){

                            $deleteData = User_location_img::where('user_location_id',$request->id)->delete();
                    }
                 
                    foreach($arrayDataimg as $valueImgs){

                    /// download img ///////////
                        if($valueImgs){

                          $imagePath = Common::imgURL_get($valueImgs,"user_location_imgs");

                        }else{
                           $imagePath = '';
                        }
                    /// download img ///////////

                        $store_imgs = User_location_img::Create(
                            [
                            'user_id' => @$userProfileData['id'],
                            'user_location_id' => @$store_notification->id,
                            'img' => @$imagePath,
                            ]
                        );
                    }
                }

                return response()->json([
                'status'=>true,
                'message' => 'Store successfully.',
                'data' => @$store_notification,
                'imgs' => @$store_imgs,
                ], 200);
            
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: list location
    ***/
    public function location_list(Request $request)
    {
        $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();

        $store_notification = User_location_details::where('user_id',$userProfileData['id'])->orderBy('id', 'desc')->get();

        
        $userData = array();
        foreach ($store_notification as  $value) {

             $ll = $value;
            // if($ll['img'] != '')
            // {
            //     $ll['img'] = url('storage/location_img')."/".$ll['img'];
            // }else{
            //     $ll['img'] = '';
            // }
            // $userData[] = $ll;

            $user_imgs_list = User_location_img::where('user_location_id',$value->id)->orderBy('id', 'desc')->get();

            $img_pathlocation = array();
            foreach ($user_imgs_list as $user_imgs_listImg) {
                
                $img_pathlocation[] = url('uploads/user_location_imgs')."/".$user_imgs_listImg->img;
            }

            $ll['img'] = $img_pathlocation;

            $userData[] = $ll;
        }

        if(count($userData) == 0){

            return response()->json([
                'status'=>true,
                'message' => 'No data found.',
                'user_data' => @$userData,
            ], 200);

        }else{

            return response()->json([
                'status'=>true,
                'message' => 'Store successfully.',
                'user_data' => @$userData,
            ], 200);

        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: list location
    ***/
    public function library_song_list_without_user(Request $request)
    {
        $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();

        $events_user = Events::where('status','active')->orderBy('id', 'desc')->get();

        $userData = array();
        foreach ($events_user as  $value) {

            $librarys_user = Librarys::where('id',$value->library_id)->where('status','active')->first();

            $librarys_audioget = Librarys_audio::where('voice_type',$userProfileData['type_of_voice'])->where('status','active')->first();

                 $ll = $value;
                if(@$librarys_audioget->mp3_file_name != '')
                {
                    $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;

                }else{

                    $ll['user_song'] = '';
                }
           
            $userData[] = $ll;
        }

        if(count($userData) == 0){

            return response()->json([
                'status'=>true,
                'message' => 'No data found.',
                'user_data' => @$userData,
            ], 200);

        }else{

            return response()->json([
                'status'=>true,
                'message' => 'User event get successfully.',
                'user_data' => @$userData,
            ], 200);

        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: remove location
    ***/
    public function location_remove(Request $request)
    {
        $rules= [
            'id' => 'required',      
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();

        $store_notification = User_location_details::where('user_id',$userProfileData['id'])->where('id',$request->id)->first();

        if($store_notification){

             $user_imgs_list = User_location_img::where('user_location_id',$store_notification->id)->orderBy('id', 'desc')->delete();

            $store_notification->delete();
             return response()->json([
                    'status'=>true,
                    'message' => 'Removed successfully.',
                ], 200);

        }else{

             return response()->json([
                    'status'=>true,
                    'message' => 'Data not found.',
                ], 200);

        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: Payment details Store
    ***/
    public function paymentStore(Request $request){

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
            'status'  => 'required',
            'transaction_platform'  => 'required',
        ]);

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $paymentDetails = Transaction::create([
                        'user_id' => @$userProfileData['id'],
                        'transaction_id' => @$request->transaction_id,
                        'subscription_id' => @$request->subscription_id,
                        'transaction_platform' => @$request->transaction_platform,
                        'transaction_status' => @$request->status,
                        'transaction_amount' => @$request->transaction_amount,
                    ]);


            return response()->json([
                'status'=>true,
                'message' => 'Payment details store successfully.',
            ], 200);
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: Profile img delete
    ***/
    public function Profile_img_delete(Request $request)
    {
        $user = User::where('id',$request->user()->id)->first();

        $file = config('settings.client_picture_folder')."/".$request->profile_picture;
        if(File::exists($file)) {
            File::delete($file);
        }

        $user['profile_picture'] = '';
        $user['default_img_id'] = '';

        $user->update();


        $getDefault_picture = Default_profile_picture::where('id',@$user->default_img_id)->first();

        if($user->default_img_id)
        {
            $user['profile_picture'] = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;

        }elseif($user['profile_picture'] != ''){
        
            $user['profile_picture'] = url(config('settings.client_picture_folder'))."/".$user['profile_picture'];
        }

         return response()->json([
                'status'=>true,
                'message' => 'Image deleted successfully.',
                'data' => @$user['profile_picture'],
            ], 200);
    }


    public function default_prayer_update_store(Request $request){

        $validator = Validator::make($request->all(), [
            'default_prayer_id' => 'required',
        ]);

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $events_user = User::where('id',$request->user()->id)->first();
        $events_user['default_prayer_id'] = $request->default_prayer_id;
        $events_user->update(); 


        //if($events_user->default_prayer_id){

              //13-12-2024  $eventGet = Events::where('id',$events_user->default_prayer_id)->first();

                //$eventGet = Events_user::where('user_id',$request->user()->id)->where('event_id',$events_user->default_prayer_id)->first();

                // Events_user::where('user_id',$request->user()->id)->where('id',$value->prayer_id)->get();

                // $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->where('voice_type',@$user->type_of_voice)->first();

                //$get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->get();

        //         $ll_song = array();
        //         foreach ($get_lib as $get_libvalue) {
                    
        //             if(@$get_libvalue->mp3_file_name != '')
        //             {
        //                 $ll['user_song'] = url('uploads/mp3')."/".@$get_libvalue->mp3_file_name;

        //                 $ll_song[] = [
        //                     'default_prayer_id' =>  @$events_user->default_prayer_id,
        //                     'default_prayer_url' =>  @$ll['user_song'],
        //                     'voice_type'  =>  @$get_libvalue->voice_type,
        //                 ];
        //             }
        //         }

        //         $default_data = [

        //             'default_id' => @$events_user->default_prayer_id,
        //             'prayer_list' => @$ll_song,
        //         ];
        // }else{

        //     $default_data = '';
        // }

        $default_data = Common::getEventUser($events_user->id,"user_id");
        return response()->json([
                'status'=>true,
                //'default_prayer' => @$default_data,
                'Events_data' => @$default_data, 
                'message' => 'Default prayer added successfully.',
            ], 200); 
    }
    
    /***
    *   Developed by: Dhruvish suthar
    *   Description: list library song
    ***/
    public function library_song_list(Request $request)
    {
        //16-12-2024 comment 
        // $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();
        // $events_user = Events_user::where('user_id',$userProfileData['id'])->where('status','active')->orderBy('id', 'desc')->get();
        // $userData = array();
        // foreach ($events_user as  $value) {

        //     $librarys_user = Librarys::where('id',$value->library_id)->where('status','active')->first();
        //     $ll = $value;

        //         $librarys_audioget = Librarys_audio::where('voice_type',@$userProfileData['type_of_voice'])->where('library_id',@$librarys_user->id)->where('status','active')->first();

        //         if(@$librarys_audioget->mp3_file_name != '')
        //         {
        //             $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;

        //         }else{

        //             $ll['user_song'] = '';
        //         }
        //     $userData[] = $ll;
        // }
        // return response()->json([
        //         'status'=>true,
        //         'message' => 'User event get successfully.',
        //         'user_data' => $userData,
        // ], 200);
        //16-12-2024 comment 

        $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();

        $events_user = Librarys::where('status','active')->orderBy('id', 'desc')->get();

        $eventData = Events::select('id','event_name')->where('status','active')->orderBy('id', 'desc')->get();

        $userData = array();
        foreach ($events_user as  $value) {

           //17-12-2024  $librarys_user = Events_user::select('event_id','event_name')->where('user_id',$userProfileData['id'])->where('library_id',$value->id)->where('status','active')->first();

            $librarys_user = Events::select('id','event_name')->where('id',@$value->events_id)->where('status','active')->first();

            $ll = $value;

                $librarys_audioget = Librarys_audio::where('voice_type',@$userProfileData['type_of_voice'])->where('library_id',@$value->id)->where('status','active')->first();

                if(@$librarys_audioget->mp3_file_name != '')
                {
                    $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;

                }else{

                    $ll['user_song'] = '';
                }

                $ll['event_data'] = @$librarys_user;

            $userData[] = $ll;
        }
        return response()->json([
                'status'=>true,
                'message' => 'library get successfully.',
                'data' => $userData,
                'eventData' => @$eventData,
        ], 200);
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: list library song like
    ***/
    public function library_song_list_like(Request $request)
    {
        $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();

        $prayer_like_unlike = Prayer_like_unlike::where('user_id',$userProfileData['id'])->where('like_unlike','1')->get();

        $eventData = Events::select('id','event_name')->where('status','active')->orderBy('id', 'desc')->get();
       // dd($prayer_like_unlike);
        //16-12-2024 
        // $userData = array();
        // foreach ($prayer_like_unlike as $key => $value) {
        //     $events_user = Events_user::where('id',$value->prayer_id)->get();
        //     foreach ($events_user as  $value) {
        //         $librarys_user = Librarys::where('id',$value->library_id)->where('status','active')->first();
        //         $librarys_audioget = Librarys_audio::where('voice_type',$userProfileData['type_of_voice'])->where('status','active')->first();
        //              $ll = $value;
        //             if($librarys_audioget->mp3_file_name != '')
        //             {
        //                 $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;
        //             }else{
        //                 $ll['user_song'] = '';
        //             }
        //         $userData[] = $ll;
        //     }
        // }
        // if($prayer_like_unlike){
        //     return response()->json([
        //         'status'=>true,
        //         'message' => 'User event get successfully.',
        //         'user_data' => $userData,
        //     ], 200);
        // }else{
        //     return response()->json([
        //         'status'=>true,
        //         'message' => 'Event data not found.!',
        //         'user_data' => $userData,
        //     ], 200);
        // }
        //16-12-2024
        $userData = array();
        foreach ($prayer_like_unlike as $key => $value) {

            $events_user = Librarys::where('id',$value->prayer_id)->where('status','active')->first();
            
            $librarys_user = Events::select('id','event_name')->where('id',@$events_user->events_id)->where('status','active')->first();

            $ll = @$events_user;

            $librarys_audioget = Librarys_audio::where('voice_type',@$userProfileData['type_of_voice'])->where('library_id',@$events_user->id)->where('status','active')->first();

            if(@$librarys_audioget->mp3_file_name != '')
            {
                $ll['user_song'] = url('uploads/mp3')."/".@$librarys_audioget->mp3_file_name;
            }else{
                $ll['user_song'] = '';
            }

             $ll['event_data'] = @$librarys_user;
            $userData[] = $ll;
        }

        if(count($prayer_like_unlike) > 0){
            return response()->json([
                'status'=>true,
                'message' => 'User library get successfully.',
                'data' => @$userData,
                'eventData' => @$eventData,
            ], 200);
        }else{
            return response()->json([
                'status'=>true,
                'message' => 'library data not found.!',
                'data' => @$userData,
                'eventData' => @$eventData,
            ], 200);
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: User event list
    ***/
    public function set_as_library(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'event_id' => 'required',
        ]);

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();
        //$events_find = Events_user::where('id',$request->id)->where('user_id',$request->user()->id)->where('event_id',@$request->event_id)->first();
        

        // if($events_find){

        //     $events_user = '';
        //     return response()->json([
        //                 'status'=>true,
        //                 'message' => 'You have already added this event.',
        //                 'user_data' => @$events_user,
        //         ], 200);

        // }else{

            $event_get = Events::select('id','event_name')->where('id',@$request->event_id)->where('status','active')->first();

           //18-12-2024 $events_user = Events_user::where('id',$request->id)->first();
            $events_user = Events_user::where('user_id',$request->user()->id)->where('event_id',$request->event_id)->first();
            //18-12-2024 $events_user['event_id'] = $event_get->id;
            //18-12-2024 $events_user['event_name'] = $event_get->event_name;
            $events_user['library_id'] = $request->id;
            $events_user->update(); 

            $librarys_audioget = Librarys_audio::where('voice_type',@$userProfileData['type_of_voice'])->where('library_id',@$events_user->library_id)->where('status','active')->first();

            if(@$librarys_audioget->mp3_file_name != '')
            {
                $ll['user_song'] = url('uploads/mp3')."/".@$librarys_audioget->mp3_file_name;
            }else{
                $ll['user_song'] = '';
            }

            @$events_user['user_song'] = @$ll['user_song'];
                return response()->json([
                        'status'=>true,
                        'message' => ' Event updated successfully.',
                        'user_data' => @$events_user,
                ], 200);
            
        //}
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: User event list
    ***/
    public function event_user_list(Request $request)
    {
        $userProfileData=$request->user()->makeHidden('date_of_birth')->toArray();

        $events_user = Events_user::where('user_id',$userProfileData['id'])->orderBy('id', 'desc')->get();

        $userData = array();
        foreach ($events_user as  $value) {

            $librarys_user = Librarys::where('id',$value->library_id)->where('status','active')->first();
            //$ll = $value;
                $ll['id'] = $value->id;
                $ll['event_name'] = $value->event_name;
                $ll['event_id'] = $value->id;
                $ll['description'] = $value->description;
                $ll['user_id'] = $value->user_id;

                $librarys_audioget = Librarys_audio::where('voice_type',@$userProfileData['type_of_voice'])->where('library_id',@$librarys_user->id)->where('status','active')->first();

                if(@$librarys_audioget->mp3_file_name != '')
                {
                    $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;

                }else{

                    $ll['user_song'] = '';
                }

                if($librarys_user){

                    $ll['library_name'] = $librarys_user->name;
                    $ll['library_id'] = $librarys_user->id;
                }else{
                    $ll['library_name'] = '';
                    $ll['library_id'] = '';
                }

            $userData[] = $ll;
        }
        return response()->json([
                'status'=>true,
                'message' => 'User event get successfully.',
                'user_data' => $userData,
        ], 200);
    }
}