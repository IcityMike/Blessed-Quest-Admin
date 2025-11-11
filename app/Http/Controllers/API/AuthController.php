<?php

namespace App\Http\Controllers\API;


use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ResetPassword;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use App\Helpers\Common;
use App\Models\Default_profile_picture;
use App\Models\Events;
use App\Models\Settings;
use App\Models\Prayer_like_unlike;
use App\Models\Events_user;
use App\Models\Librarys_audio;
use App\Models\Librarys;
use App\Models\Notifications;
use App\Models\User_notifications_settings;
use Twilio\Rest\Client;
use App\PasswordReset;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use Str;
use Validator;

class AuthController extends Controller
{
    protected function setData($value)
    {
        array_walk_recursive($value, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });
        return $value;;
    }
    /** 
     * success response method.
     *
     * @return \Illuminate\Http\Response
     **/
    public function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            // 'user_status' => $result->status,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

     /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
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


    /***
    *   Developed by: Dhruvish suthar
    *   Description: Save client detail in database and send welcome email
    ***/
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
             //'first_name' => 'required|string',
            // 'last_name' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
            // 'confirm_password' => 'required|same:password',
            // 'phone' => 'digits:10',
            // 'postal_address_line1' => 'required|string',
            // 'postal_address_line2' => 'required|string',
            // 'country_id' => 'required|integer',
            // 'state' => 'required|string',
            // 'city' => 'required|string',
            'device_id' => 'required',
            'device_token' => 'required',
            'type' => 'required|in:android,ios',
        ],[
            'type.in' => 'Device type is either android or ios'
        ]);

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        $user = User::whereEmail($request->email)->first();
        if($user)
        { 
           // return $this->sendError(__('This email address has already been used.')); 

             return response()->json([
                'status'=>true,
                'message' => 'This email address has already been used.',
            ], 200);

        }else{
            $dob = NULL;
            if($request->date_of_birth)
            {
                $dob = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
            }


            $fileName = '';
            if($request->profile_picture)
            {
                $file = config('settings.client_picture_folder')."/".$request->profile_picture;
                if(File::exists($file)) {
                    File::delete($file);
                }

                 $fileName = "picture_".time().".".$request->profile_picture->getClientOriginalExtension();

                $path = $request->profile_picture->move(config('settings.client_picture_folder'),$fileName);
            }
           
            $settings = Settings::first();

            $user = User::create([
              //  'first_name' => $request->first_name,
               // 'last_name' => @$request->last_name,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone_number' => $request->phone_number,
                'date_of_birth' => $dob,
                'gender' => $request->gender,
                'postal_address' => $request->postal_address,
                'postal_address_line1' => $request->postal_address_line1,
                'postal_address_line2' => $request->postal_address_line2,
                'country_id' => $request->country_id,
                'state' => $request->state,
                'city' => $request->city,
                'profile_picture' => @$fileName,
                'default_img_id' => '2',
                'login_type' => '0',
                'type_of_voice' => @$settings->type_of_voice_id,
                'default_prayer_id' => @$settings->default_event_id,
                'language' => 'English',
               // 'task_create_date' => 
            ]);

            $otp = rand(1231,7879);
            $user->otp = $otp;
            $user->save();

            $m_data = [
                'bankaccountName' => $request->name,
                'isActive' => true
            ];
            // create automatcher account in monoova

            if($request->device_token && $request->type){
                $device = UserDevice::updateOrCreate(
                    [
                        'user_id' =>$user->id,
                        // 'device_id' =>$request->device_id
                    ],
                    [
                        'user_id' => $user->id,
                        'device_id' => $request->device_id,
                        'device_token' =>$request->device_token,
                        'type' => $request->type,
                    ]
                );

                // $user['device_id'] = ($user->userDevice) ? $user->userDevice->device_id : ' ';
                // $user['device_token'] = ($user->userDevice) ? $user->userDevice->device_token : ' ';
                // $user['name'] = $user->first_name.' '.$user->last_name;
                $user['device_id'] = ($device) ? $device->device_id : ' ';
                $user['device_token'] = ($device) ? $device->device_token : ' ';
            }
            
            $created_user = User::where('id',$user->id)->first();

            $user_token =  $created_user->createToken('blessed_quest')->accessToken; 

            ////////////////// default event store start ////////////////////////
                    // $defaulteventlike = Prayer_like_unlike::create([
                    //     'user_id' => @$user->id,
                    //     'prayer_id' => @$settings->default_event_id,
                    //     'like_unlike' => '1',
                    // ]);

            ////////////////// default event store start ////////////////////////

            // if($user['default_img_id']!= '' )
            // {
            //     $getDefault_picture = Default_profile_picture::where('id',@$user['default_img_id'])->first();

            //     $created_user['profile_picture'] = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;

            // }elseif($user['profile_picture']!= ''){
                
            //     $created_user['profile_picture'] = url(config('settings.client_picture_folder'))."/".$user['profile_picture'];
            // }else{

            //     $created_user['profile_picture'] = url('uploads/default_profile_picture')."/Icon_logo.png";
            // }


            if($user['default_img_id']!= '' )
            {
                $getDefault_picture = Default_profile_picture::where('id',@$user['default_img_id'])->first();

                $created_user['profile_picture'] = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;

            }elseif($user['profile_picture']!= ''){
                
                $created_user['profile_picture'] = url(config('settings.client_picture_folder'))."/".$user['profile_picture'];
            }

            ////////////////// event add by user start ////////////////////////

            $get_event = Events::where('status','active')->get();

            foreach ($get_event as $eventValue) {
                
                    $userevent = Events_user::create([
                        'event_name' => $eventValue->event_name,
                        'user_id' => @$user->id,
                        'event_id' => $eventValue->id,
                        'library_id' => $eventValue->library_id,
                        'admin_id' => $eventValue->admin_id,
                    ]);

            }

            ////////////////// event add by user end ////////////////////////

            ///////////////// User notifications settings start //////////////////

            $store_notification = User_notifications_settings::create([
                'user_id' => $user->id,
                'all_push_notifications' => '0',
                'traffic_route_updates' => '0',
                'receive_recommended_content'  => '0',
                'reminders_credits_expire'  => '0',
                'reminders_sub_autorenews'  => '0',
                'notify_updates_changes_account'  => '0',
                'notify_me_sub_and_sub_renewal_errors'  => '0',
                'email_notifications'  => '0',
                'receive_reminders_days_before_credits_expire_email'  => '0',
                'reminders_sub_autorenews_email'  => '0',
                'notify_updates_changes_account_email'  => '0',
                'notify_me_sub_and_sub_renewal_errors_email'  => '0',
            ]);

            ///////////////// User notifications settings end //////////////////
            $getNotifications_count = Notifications::where('user_id',$user->id)->where('notify_read_unread','1')->orderBy('id', 'desc')->get();

            $NotificationsCount = @$getNotifications_count->count();

                // if($user->default_prayer_id){

                //         $eventGet = Events::where('id',$user->default_prayer_id)->first();

                //         // $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->where('voice_type',@$user->type_of_voice)->first();

                //         $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->get();

                //         $ll_song = array();
                //         foreach ($get_lib as $get_libvalue) {
                            
                //             if(@$get_libvalue->mp3_file_name != '')
                //             {
                //                 $ll['user_song'] = url('uploads/mp3')."/".@$get_libvalue->mp3_file_name;

                //                 $ll_song[] = [
                //                     'default_prayer_id' =>  @$user->default_prayer_id,
                //                     'default_prayer_url' =>  @$ll['user_song'],
                //                     'voice_type'  =>  @$get_libvalue->voice_type,
                //                 ];
                //             }
                //         }
                //         $default_data = [
                //             'default_id' => @$user->default_prayer_id,
                //             'prayer_list' => @$ll_song,
                //         ];
                // }else{
                //     $default_data = '';
                // }

                // if($user->default_prayer_id){

                //     $events_user = Librarys::select('id','name')->where('status','active')->orderBy('id', 'desc')->get();

                //     $eventData = array();
                //     foreach ($events_user as  $value) {

                //         $librarys_user = Events_user::select('event_id','event_name')->where('user_id',$user->id)->where('library_id',$value->id)->where('status','active')->first();

                //         $ll = $value;

                //             $librarys_audioget = Librarys_audio::where('voice_type',@$user->type_of_voice)->where('library_id',@$value->id)->where('status','active')->first();

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
                $default_data = Common::getEventUser($user->id,"user_id");
                //// email //////
                    try { 
                        /*** Send welcome email to user by email ***/
                        $data = User::find($user->id)->toArray();
                        $data['clientId'] = $user->id;
                        $data['password'] = $request->password;
                        $data['email'] = $request->email;
                        $data['name'] = ($request->name) ? $request->name : $request->first_name.' '.$request->last_name;
                        $data['email_verification_otp'] = $otp;
                        $user->sendWelcomeNotification($data);
                    //  $user->sendEmailVerificationOtpNotification($data);
                    } 

                    catch (\Exception $e) { 
                        //return back()->with('error',"User registered successfully, Mail not sent please contact to administrator"); 

                        return response()->json([
                            'status'=>false,
                            'message' => 'User registered successfully, Mail not sent please contact to administrator.',
                        ], 200);
                    } 

                ///////////////

            return response()->json([
                'status'=>true,
                'message' => 'Registration completed successfully.',
                'data' => $this->setData($created_user->toArray()),
                'token' => $user_token,
                'login_type' => 'normal',
                'notification_count' => @$NotificationsCount,
                'Events_data' => @$default_data,
            ], 200);
            // return $this->sendResponse($user, __('Registration completed successfully.'));
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: register with social account user from 
    ***/
    // public function social_register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required',
    //         'device_id' => 'required',
    //         'device_token' => 'required',
    //         'type' => 'required|in:android,ios',
    //         'login_type' => 'required|in:google,apple',
    //     ],[
    //         'type.in' => 'Device type is either android or ios',
    //         'login_type.in' => 'Social type is either google or apple'
    //     ]);
    //     if($validator->fails())
    //     {
    //         return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
    //     }

    //     if($request->login_type == 'apple'){

    //         $loginSocial_type = '1';

    //     }elseif($request->login_type == 'google'){

    //         $loginSocial_type = '2';
    //     }else{

    //         $loginSocial_type = '0';
    //     }


    //     $user = User::whereEmail($request->email)->first();
    //     if($user)
    //     { 
    //          return response()->json([
    //             'status'=>true,
    //             'message' => 'This email address has already been used.',
    //         ], 200);

    //     }else{
    //         $dob = NULL;
    //         if($request->date_of_birth)
    //         {
    //             $dob = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
    //         }

    //         $fileName = '';

    //         $settings = Settings::first();

    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => bcrypt($request->password),
    //             'phone_number' => $request->phone_number,
    //             'date_of_birth' => $dob,
    //             'gender' => $request->gender,
    //             'profile_picture' => @$fileName,
    //             'default_img_id' => '2',
    //             'login_type' => $loginSocial_type,
    //             'type_of_voice' => @$settings->type_of_voice_id,
    //             'default_prayer_id' => @$settings->default_event_id,
    //         ]);

    //         $otp = rand(1231,7879);
    //         $user->otp = $otp;
    //         $user->save();

    //         if($request->device_token && $request->type){
    //             $device = UserDevice::updateOrCreate(
    //                 [
    //                     'user_id' =>$user->id,
    //                     // 'device_id' =>$request->device_id
    //                 ],
    //                 [
    //                     'user_id' => $user->id,
    //                     'device_id' => $request->device_id,
    //                     'device_token' =>$request->device_token,
    //                     'type' => $request->type,
    //                 ]
    //             );

    //             // $user['device_id'] = ($user->userDevice) ? $user->userDevice->device_id : ' ';
    //             // $user['device_token'] = ($user->userDevice) ? $user->userDevice->device_token : ' ';
    //             // $user['name'] = $user->first_name.' '.$user->last_name;
    //             $user['device_id'] = ($device) ? $device->device_id : ' ';
    //             $user['device_token'] = ($device) ? $device->device_token : ' ';

    //         }

    //         $created_user = User::where('id',$user->id)->first();

    //         $user_token =  $created_user->createToken('blessed_quest')->accessToken; 

    //         ////////////////// event add by user start ////////////////////////

    //         $get_event = Events::where('status','active')->get();

    //         foreach ($get_event as $eventValue) {
                
    //                 $user = Events_user::create([
    //                     'event_name' => $eventValue->event_name,
    //                     'user_id' => @$user->id,
    //                     'event_id' => $eventValue->id,
    //                     'library_id' => $eventValue->library_id,
    //                     'admin_id' => $eventValue->admin_id,
    //                 ]);

    //         }

    //         ////////////////// event add by user end ////////////////////////

    //         /////// email //////

    //          try { 
    //             /*** Send welcome email to user by email ***/
    //             $data = User::find($user->id)->toArray();
    //             $data['clientId'] = $user->id;
    //             $data['password'] = $request->password;
    //             $data['email'] = $request->email;
    //             $data['name'] = ($request->name) ? $request->name : $request->first_name.' '.$request->last_name;
    //             $data['email_verification_otp'] = $otp;
    //           //  $user->sendWelcomeNotification($data);
    //             //$user->sendEmailVerificationOtpNotification($data);
    //         } 
    //         catch (\Exception $e) { 
    //             //return back()->with('error',"User registered successfully, Mail not sent please contact to administrator"); 

    //                 return response()->json([
    //                     'status'=>false,
    //                     'message' => 'User registered successfully, Mail not sent please contact to administrator.',
    //                 ], 200);
    //         } 

    //         /////////////////////

    //         return response()->json([
    //             'status'=>true,
    //             'message' => 'Registration completed successfully.',
    //             'data' => $this->setData($created_user->toArray()),
    //             'token' => $user_token,
    //         ], 200);
    //         // return $this->sendResponse($user, __('Registration completed successfully.'));
    //     }
    // }



    /***
    *   Developed by: Dhruvish suthar
    *   Description: Delete Account user
    ***/
    public function deleteAccount(Request $request)
    {

        $user = User::where('id',$request->user()->id)->delete();

        $device = UserDevice::where('user_id',$request->user()->id)->delete();

        $events_user_delete = Events_user::where('user_id',$request->user()->id)->delete();

        return response()->json([
            'status'=>true,
            'message' => 'Account deleted successfully.',
        ], 200);
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: login with social account user from 
    ***/
    public function social_login_register(Request $request)
    {

        $user = User::where('email', $request->email)->first();
   
        if($user){

            $validator = Validator::make($request->all(), [
            'email' => 'required',
            'device_id' => 'required',
            'device_token' => 'required',
            'type' => 'required|in:android,ios',
            'login_type' => 'required|in:google,apple',
            ],[
                'type.in' => 'Device type is either android or ios',
                'login_type.in' => 'Social type is either google or apple'
            ]);
            if($validator->fails())
            {
                return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
            }

            if($request->login_type == 'apple'){

                $loginSocial_type = '1';

            }elseif($request->login_type == 'google'){

                $loginSocial_type = '2';
            }else{

                $loginSocial_type = '0';
            }

            // return response()->json([
            //     'status'=>true,
            //     'message' => 'Credentials does not match with our records.',
            // ], 200);

            Auth::guard('client')->login($user,isset($request->remember));
                // $user['token'] =  $user->createToken('blessed_quest')->accessToken; 
            $user['name'] =  (@$user->name) ? @$user->name : @$user->first_name.' '.@$user->last_name;

            //10-12-2024 $user->profile_picture = @$user->profile_picture ? url(config('settings.client_picture_folder'))."/".@$user->profile_picture : url(config('settings.default_picture'));

            if($user['default_img_id'])
            {
                $getDefault_picture = Default_profile_picture::where('id',@$user['default_img_id'])->first();

                $user['profile_picture'] = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;

            }elseif($user['profile_picture']!= ''){
                
                $user['profile_picture'] = url(config('settings.client_picture_folder'))."/".$user['profile_picture'];
            }

            if($request->device_token && $request->type){
                $device = UserDevice::updateOrCreate(
                    [
                        'user_id' =>$user->id,
                    ],
                    [
                        'user_id' => $user->id,
                        'device_id' => $request->device_id,
                        'device_token' =>$request->device_token,
                        'type' => $request->type,
                    ]
                );

                $user['device_id'] = ($device) ? $device->device_id : ' ';
                $user['device_token'] = ($device) ? $device->device_token : ' ';

            }

            $user_token =  $user->createToken('blessed_quest')->accessToken;

            $getNotifications_count = Notifications::where('user_id',$user->id)->where('notify_read_unread','1')->orderBy('id', 'desc')->get();

            $NotificationsCount = @$getNotifications_count->count();

                // if($user->default_prayer_id){
                //         $eventGet = Events::where('id',$user->default_prayer_id)->first();
                //         // $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->where('voice_type',@$user->type_of_voice)->first();
                //         $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->get();

                //         $ll_song = array();
                //         foreach ($get_lib as $get_libvalue) {
                //             if(@$get_libvalue->mp3_file_name != '')
                //             {
                //                 $ll['user_song'] = url('uploads/mp3')."/".@$get_libvalue->mp3_file_name;
                //                 $ll_song[] = [
                //                     'default_prayer_id' =>  @$user->default_prayer_id,
                //                     'default_prayer_url' =>  @$ll['user_song'],
                //                     'voice_type'  =>  @$get_libvalue->voice_type,
                //                 ];
                //             }
                //         }
                //     $default_data = [
                //         'default_id' => @$user->default_prayer_id,
                //         'prayer_list' => @$ll_song,
                //     ];
                // }else{
                //     $default_data = '';
                // }

                // if($user->default_prayer_id){

                //     $events_user = Librarys::select('id','name')->where('status','active')->orderBy('id', 'desc')->get();

                //     $eventData = array();
                //     foreach ($events_user as  $value) {

                //         $librarys_user = Events_user::select('event_id','event_name')->where('user_id',$user->id)->where('library_id',$value->id)->where('status','active')->first();

                //         $ll = $value;

                //             $librarys_audioget = Librarys_audio::where('voice_type',@$user->type_of_voice)->where('library_id',@$value->id)->where('status','active')->first();

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

            $default_data = Common::getEventUser($user->id,"user_id");

            return response()->json([
                'status'=>true,
                'message' => 'User login successfully.',
                'data' => $user,
                'token' => $user_token,
                'login_type' => $request->login_type,
                'notification_count' => @$NotificationsCount,
                'Events_data' => @$default_data,
            ], 200);

        }else{ 

            if($request->login_type == 'apple'){

                $loginSocial_type = '1';

            }elseif($request->login_type == 'google'){

                $loginSocial_type = '2';
            }else{

                $loginSocial_type = '0';
            }

            $dob = NULL;
            if($request->date_of_birth)
            {
                $dob = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
            }

            $fileName = '';

            $settings = Settings::first();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone_number' => $request->phone_number,
                'date_of_birth' => $dob,
                'gender' => $request->gender,
                'profile_picture' => @$fileName,
                'default_img_id' => '2',
                'login_type' => $loginSocial_type,
                'type_of_voice' => @$settings->type_of_voice_id,
                'default_prayer_id' => @$settings->default_event_id,
                'language' => 'English',
            ]);

            $otp = rand(1231,7879);
            $user->otp = $otp;
            $user->save();

            if($request->device_token && $request->type){
                $device = UserDevice::updateOrCreate(
                    [
                        'user_id' =>$user->id,
                        // 'device_id' =>$request->device_id
                    ],
                    [
                        'user_id' => $user->id,
                        'device_id' => $request->device_id,
                        'device_token' =>$request->device_token,
                        'type' => $request->type,
                    ]
                );

                // $user['device_id'] = ($user->userDevice) ? $user->userDevice->device_id : ' ';
                // $user['device_token'] = ($user->userDevice) ? $user->userDevice->device_token : ' ';
                // $user['name'] = $user->first_name.' '.$user->last_name;
                $user['device_id'] = ($device) ? $device->device_id : ' ';
                $user['device_token'] = ($device) ? $device->device_token : ' ';

            }

            ////////////////// default event store start ////////////////////////
                    // $defaulteventlike = Prayer_like_unlike::create([
                    //     'user_id' => @$user->id,
                    //     'prayer_id' => @$settings->default_event_id,
                    //     'like_unlike' => '1',
                    // ]);

            ////////////////// default event store start ////////////////////////

            
            $created_user = User::where('id',$user->id)->first();

            if($created_user->login_type == 0){

                $login_type_show = 'normal';

            }elseif($created_user->login_type == 1)
            {
                $login_type_show = 'apple';

            }elseif($created_user->login_type == 2){

                $login_type_show = 'google';
            }

            if($user['default_img_id'])
            {
                $getDefault_picture = Default_profile_picture::where('id',@$user['default_img_id'])->first();

                $created_user['profile_picture'] = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;

            }elseif($user['profile_picture']!= ''){
                
                $created_user['profile_picture'] = url(config('settings.client_picture_folder'))."/".$user['profile_picture'];
            }


            $user_token =  $created_user->createToken('blessed_quest')->accessToken; 

            ////////////////// event add by user start ////////////////////////

            $get_event = Events::where('status','active')->get();

            foreach ($get_event as $eventValue) {
                
                    $userevent = Events_user::create([
                        'event_name' => $eventValue->event_name,
                        'user_id' => @$user->id,
                        'event_id' => $eventValue->id,
                        'library_id' => $eventValue->library_id,
                        'admin_id' => $eventValue->admin_id,
                    ]);

            }

            ////////////////// event add by user end ////////////////////////

            $getNotifications_count = Notifications::where('user_id',$user->id)->where('notify_read_unread','1')->orderBy('id', 'desc')->get();

            $NotificationsCount = @$getNotifications_count->count();

                //16-12-2024
                // if($user->default_prayer_id){
                //         $eventGet = Events::where('id',$user->default_prayer_id)->first();
                //         // $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->where('voice_type',@$user->type_of_voice)->first();
                //         $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->get();
                //         $ll_song = array();
                //         foreach ($get_lib as $get_libvalue) {
                //             if(@$get_libvalue->mp3_file_name != '')
                //             {
                //                 $ll['user_song'] = url('uploads/mp3')."/".@$get_libvalue->mp3_file_name;

                //                 $ll_song[] = [
                //                     'default_prayer_id' => @$user->default_prayer_id,
                //                     'default_prayer_url' =>  @$ll['user_song'],
                //                     'voice_type'  =>  @$get_libvalue->voice_type,
                //                 ];
                //             }
                //         }
                //         $default_data = [
                //             'default_id' => @$user->default_prayer_id,
                //             'prayer_list' => @$ll_song,
                //         ];
                // }else{
                //     $default_data = '';
                // }
                //16-12-2024

                // if($user->default_prayer_id){

                //     $events_user = Librarys::select('id','name')->where('status','active')->orderBy('id', 'desc')->get();

                //     $eventData = array();
                //     foreach ($events_user as  $value) {

                //         $librarys_user = Events_user::select('event_id','event_name')->where('user_id',$user->id)->where('library_id',$value->id)->where('status','active')->first();

                //         $ll = $value;

                //             $librarys_audioget = Librarys_audio::where('voice_type',@$user->type_of_voice)->where('library_id',@$value->id)->where('status','active')->first();

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

                $default_data = Common::getEventUser($user->id,"user_id");

                try { 
                    /*** Send welcome email to user by email ***/
                    $data = User::find($user->id)->toArray();
                    $data['clientId'] = $user->id;
                    $data['password'] = $request->password;
                    $data['email'] = $request->email;
                    $data['name'] = ($request->name) ? $request->name : $request->first_name.' '.$request->last_name;
                    $data['email_verification_otp'] = $otp;
                    $user->sendWelcomeNotification($data);
                   // $user->sendEmailVerificationOtpNotification($data);

                }catch (\Exception $e) { 
                    
                    return response()->json([
                        'status'=>false,
                        'message' => 'User registered successfully, Mail not sent please contact to administrator.',
                    ], 200);
                } 


            return response()->json([
                'status'=>true,
                'message' => 'Registration completed successfully.',
                'data' => $this->setData($created_user->toArray()),
                'token' => $user_token,
                'login_type' => $login_type_show,
                'notification_count' => @$NotificationsCount,
                'Events_data' => @$default_data,
            ], 200);
        }
    }
    /***
    *   Developed by: Dhruvish suthar
    *   Description: login user from session
    ***/
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'device_id' => 'required',
            'device_token' => 'required',
            'type' => 'required|in:android,ios',
        ],[
            'type.in' => 'Device type is either android or ios'
        ]);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $user = User::where('email', $request->email)->first();
       // dd($user,$user->isEmailVerifed);
        if(!$user){
            return response()->json([
                'status'=>true,
               // 'message' => 'Credentials does not match with our records.',

               'message'=> "The email address you entered does not match any account.",
            ], 200);

        }elseif($user->status != 'active'){

            return response()->json([
                'status'=>true,
                'message' => 'Your account is inactive please contact to admin.!',
            ], 200);

        }else{ 
            // if($user->isEmailVerifed == "0"){

            //     $email_otp = rand(1231,7879);
            //     $user->email_verification_otp = $email_otp;
            //     $user->update();

            //     $data = $user->toArray();
            //     $data['clientId'] = $user->id;
            //     $data['name'] = ($user->name) ? $user->name : $user->first_name.' '.$user->last_name;

            //     $user->sendEmailresendVerificationOtpNotification($data);

            //      return $this->sendResponse($user, 'Your account is not verify otp again sent your email.');
            // }
           // dd($request->password);
            if (Hash::check($request->password, $user->password)) {
                if($user->status == "block" || $user->status == "inactive"){
                    return $this->sendError(__('Your account is deactivated.'));
                }

                Auth::guard('client')->login($user,isset($request->remember));
                // $user['token'] =  $user->createToken('blessed_quest')->accessToken; 
                $user['name'] =  ($user->name) ? $user->name : $user->first_name.' '.$user->last_name;
                
                //10-12-2024 $user->profile_picture = $user->profile_picture ? url(config('settings.client_picture_folder'))."/".$user->profile_picture : url(config('settings.default_picture'));

                if($user->profile_picture != '')
                {
                    $user->profile_picture = url(config('settings.client_picture_folder'))."/".$user->profile_picture;

                }elseif($user->default_img_id){
                    
                    $getDefault_picture = Default_profile_picture::where('id',@$user->default_img_id)->first();

                    $user->profile_picture = url('uploads/default_profile_picture')."/".$getDefault_picture->image_name;
                }

                if($request->device_token && $request->type){
                    $device = UserDevice::updateOrCreate(
                        [
                            'user_id' =>$user->id,
                        ],
                        [
                            'user_id' => $user->id,
                            'device_id' => $request->device_id,
                            'device_token' =>$request->device_token,
                            'type' => $request->type,
                        ]
                    );

                    $user['device_id'] = ($device) ? $device->device_id : ' ';
                    $user['device_token'] = ($device) ? $device->device_token : ' ';
                }

                 $user_token =  $user->createToken('blessed_quest')->accessToken; 
                 //return $this->sendResponse($user, 'User login successfully.');

                 $getNotifications_count = Notifications::where('user_id',$user->id)->where('notify_read_unread','1')->orderBy('id', 'desc')->get();

                $NotificationsCount = @$getNotifications_count->count();

                //16-12-2024
                // if($user->default_prayer_id){
                //     $eventGet = Events::where('id',$user->default_prayer_id)->first();

                //     // $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->where('voice_type',@$user->type_of_voice)->first();

                //     $get_lib = Librarys_audio::where('library_id',@$eventGet->library_id)->get();

                //     $ll_song = array();
                //     foreach ($get_lib as $get_libvalue) {
                        
                //         if(@$get_libvalue->mp3_file_name != '')
                //         {
                //             $ll['user_song'] = url('uploads/mp3')."/".@$get_libvalue->mp3_file_name;

                //             $ll_song[] = [
                //                 'default_prayer_id' =>  @$user->default_prayer_id,
                //                 'default_prayer_url' =>  @$ll['user_song'],
                //                 'voice_type'  =>  @$get_libvalue->voice_type,
                //             ];
                //         }
                //     }

                //     $default_data = [

                //         'default_id' => @$user->default_prayer_id,
                //         'prayer_list' => @$ll_song,
                //     ];
                // }else{

                //     $default_data = '';
                // }
                //16-12-2024


                //if($user->default_prayer_id){

                    //17-12-2024
                    // $events_user = Librarys::select('id','name')->where('status','active')->orderBy('id', 'desc')->get();

                    // $eventData = array();
                    // foreach ($events_user as  $value) {

                    //     $librarys_user = Events_user::select('event_id','event_name')->where('user_id',$user->id)->where('library_id',$value->id)->where('status','active')->first();

                    //     $ll = $value;

                    //         $librarys_audioget = Librarys_audio::where('voice_type',@$user->type_of_voice)->where('library_id',@$value->id)->where('status','active')->first();

                    //         if(@$librarys_audioget->mp3_file_name != '')
                    //         {
                    //             $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;

                    //         }else{

                    //             $ll['user_song'] = '';
                    //         }

                    //         $ll['event_id'] = @$librarys_user->event_id;
                    //         $ll['event_name'] = @$librarys_user->event_name;

                    //     $eventData[] = $ll;
                    // }

                    // $default_data = [

                    //     //'default_id' => @$user->default_prayer_id,
                    //     'Events_data' => @$eventData,
                    // ];
                    //$default_data = @$eventData;
                    //17-12-2024
                // }else{

                //     $default_data = '';
                // }

                 $default_data = Common::getEventUser($user->id,"user_id");

                 return response()->json([
                    'status'=>true,
                    'message' => 'User login successfully.',
                    'data' => $user,
                    'token' => $user_token,
                    'login_type' => 'normal',
                    'notification_count' => @$NotificationsCount,
                    'Events_data' => @$default_data,
                ], 200);
            }else{

                return response()->json([
                    'status'=>true,
                   // 'message' => 'Credentials does not match with our records.',

                    'message' => 'Incorrect password. Please try again.',
                ], 200);
            }
        
            return $this->sendResponse($user, 'User login successfully.');
        }        
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: logged out login user
    ***/
    public function logout(Request $request)
    {
        $user = $request->user();
        //delete user device
        UserDevice::where('user_id',$user->id)->delete();

        $request->user()->token()->revoke();
        
        return response()->json([
            'status'=>true,
            'message' => 'User logout Successfully.'
        ],200);
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: deactivate login user
    ***/
    public function deactivateUser(Request $request)
    {
        $user = $request->user();
        if($user){
            // deactivate account by status block
            $user->status = "block";
            $user->save();

            //delete user device
            UserDevice::where('user_id',$user->id)->delete();

            $request->user()->token()->revoke();

            return response()->json([
                'status'=>true,
                'message' => 'Successfully Deactivated Account'
            ],200);
        }else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'],200);
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: send forgot password link email to request user
    ***/
    public function ForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        $user = User::where('email', $request->email)->first();
        if(!$user)
        {
            return response()->json([
                'status'=>false,
                'message' => 'We can not find a user with that email address.'
            ], 200);
        }
        $token_reset = Password::broker()->createToken($user);
        try
        {
            $otp = rand(1231,7879);
            $user->otp = $otp;
            $user->otp_created_at = now()->addMinutes(10);
            $user->save();

            $data = $user->toArray();
            $data['clientId'] = $user->id;
            $data['name'] = ($user->first_name) ? $user->first_name.' '.$user->last_name : $user->name;
          $user->sendForgotPasswordOtpNotification($data);

            return response()->json([
                'status'=>true,
                'userId'=> $user->id,
                'otp'=> $otp,
                'message' => 'We have e-mailed otp for password reset!'
            ],200);

            // $user->notify(
            //     new ResetPassword($token_reset,"client")
            // );
            // return response()->json([
            //     'status'=>true,
            //     'message' => 'We have e-mailed your password reset link!'
            // ]);

        }
        catch(\Exception $e)
        {
            //return back()->with('error',"Mail not sent please contact to administrator",200);

            return response()->json([
                'status'=>false,
                'message' => 'Mail not sent please contact to administrator.',
            ], 200);
        }
    }  

    /***
    *   Developed by: Dhruvish suthar
    *   Description: verify given otp
    ***/
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|integer',
            'email' => 'required|email',
        ]);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        $user = User::where('email', $request->email)->first();
        if(!$user)
        {
            return response()->json([
                'status'=>false,
                'message' => 'We can\'t find a user.'
            ], 200);
        }else{
            // if($user->otp == $request->otp){
            //     return response()->json([
            //         'status'=>true,
            //         'data' => $this->setData($user->toArray()),
            //         'message' => 'OTP verified successfully.!'
            //     ],200);
            // }else{
            //     return response()->json([
            //         'status'=>false,
            //         'message' => 'Incorrect OTP.'
            //     ], 200);
            // }

            if(strtotime($user->otp_created_at) < strtotime(now())) 
            {
                   // return false; //invalid
                return response()->json([
                    'status'=>false,
                    'message' => 'OTP Expired.'
                ], 200);
            }
            if($user->otp == $request->otp){

                return response()->json([
                    'status'=>true,
                    'message' => 'OTP verified successfully.!'
                ],200);
                
            }
            else{
                return response()->json([
                    'status'=>false,
                    'message' => 'Incorrect OTP.'
                ], 200);
            }
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: verify given otp
    ***/
    public function verifyOtpforlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|integer',
            'email' => 'required|email',
        ]);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        $user = User::where('email', $request->email)->first();
        if(!$user)
        {
            return response()->json([
                'status'=>false,
                'message' => 'We can\'t find a user.'
            ], 200);
        }else{
            if($user->email_verification_otp == $request->otp){

                $input['isEmailVerifed'] = '1';
                $user->update($input);

                return response()->json([
                    'status'=>true,
                    'data' => $this->setData($user->toArray()),
                    'message' => 'OTP verified successfully.!'
                ],200);
            }else{
                return response()->json([
                    'status'=>false,
                    'message' => 'Incorrect OTP.'
                ], 200);
            }
            
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password|min:6'
        ]);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $user = User::whereEmail($request->email)->first();
        if(!$user)
        {
            return response()->json([
                'status'=>false,
                'message' => 'We can\'t find a user with that e-mail address.'
            ], 200);
        }

        try
        {
            $input['password'] = bcrypt($request->password);
            $user->update($input);

            /*** Send change password notification to user by email ***/
             $user->sendPasswordResetSuccessNotification($user);

            return response()->json([
                'status'=>true,
                'data' => $this->setData($user->toArray()),
                'message' => 'Password changed successfully.!'
            ]);

        }
        catch(\Exception $e)
        {
            //return back()->with('error',"Mail not sent please contact to administrator");

            return response()->json([
                'status'=>false,
                'message' => 'Mail not sent please contact to administrator.',
            ], 200);
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description: verify email address
    ***/
    public function verifyEmailAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_verification_otp' => 'required|integer',
            // 'email' => 'required|email',
        ]);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        $user = $request->user();
        if(!$user)
        {
            return response()->json([
                'status'=>false,
                'message' => 'We can\'t find a user.'
            ], 200);

        }else{
            if($user->email_verification_otp == $request->email_verification_otp){

                $user->isEmailVerifed = '1';
                $user->save();

                return response()->json([
                    'status'=>true,
                    'user_status' => $user->status,
                    'data' => $this->setData($user->toArray()),
                    'message' => 'Email verified successfully.!'
                ],200);
            }else{
                return response()->json([
                    'status'=>false,
                    'message' => 'Incorrect OTP.'
                ], 200);
            }
            
        }
    }

    /***
    *   Developed by: Dhruvish suthar
    *   Description: verify phone number
    ***/
    public function verifyPhoneNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_no_verification_otp' => 'required|integer',
        ]);
        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
        $user = $request->user();
        if(!$user)
        {
            return response()->json([
                'status'=>false,
                'message' => 'We can\'t find a user.'
            ], 200);

        }else{
            if($user->phone_no_verification_otp == $request->phone_no_verification_otp){

                $user->isPhoneNumberVerified = '1';

                $user->save();

                return response()->json([
                    'status'=>true,
                    'user_status' => $user->status,
                    'data' => $this->setData($user->toArray()),
                    'message' => 'Phone number verified successfully.!'
                ],200);
            }else{
                return response()->json([
                    'status'=>false,
                    'message' => 'Incorrect OTP.'
                ], 200);
            }
            
        }
    }
    

    public function sendPhoneNumberOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|integer'
        ]);

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $user = $request->user();

        if(!$user){
            return response()->json([
                'status'=>false,
                'message' => 'We can\'t find a user.'
            ], 200);
        }else{

            $user->phone_number = $request->phone_number;
            $user->isPhoneNumberVerified = '0';
            // $user->phone_no_verification_otp = rand(1236,8956);
            $user->phone_no_verification_otp = 1234;
            $user->update();

            $sms_message = "Thank you for choosing a MoneyApp.Your phone number verication code is: ".$user->phone_no_verification_otp;

                $receiverNumber = "+91".$user->phone_number;
                Common::sendSMSToClient($sms_message,$receiverNumber);

            return response()->json([
                'status'=>true,
                'user_status' => $user->status,
                'data' => $this->setData($user->toArray()),
                'message' => 'Phone number verication otp sent successfully.!'
            ],200);

        }
    }

    public function emailVerificationResendOTP(Request $request)
    {
        $user = $request->user();
        if(!$user){
            return response()->json([
                'status'=>false,
                'message' => 'We can\'t find a user.'
            ], 200);
        }else{
             $user->email_verification_otp = rand(1236,8956);
            //$user->email_verification_otp = 1234;
            $user->update();

            $data = User::find($user->id)->toArray();
            $data['clientId'] = $user->id;
            $data['password'] = $request->password;
            $data['name'] = ($request->name) ? $request->name : $request->first_name.' '.$request->last_name;

            $user->sendEmailresendVerificationOtpNotification($data);

            return response()->json([
                'status'=>true,
                'user_status' => $user->status,
                'data' => $this->setData($user->toArray()),
                'message' => 'Email verication otp sent successfully.!'
            ],200);

        }
    }


    /***
     * Developed by: Dhruvish Suthar
     * API: Reset Password API
     * Description: Validate user reset password request.
     * If invalid, Return error message.
     * If valid, Set new password for user and Return success message.
     */
   public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required',
            'verification_code' => 'required',
            'new_password' => 'required|min:6|max:25',
            'confirm_new_password' => 'required|same:new_password',
        ]);

       // if($validator->fails()){
           
          // return $this->sendError($validator->getMessageBag()->first(),200); 
        //}

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $user_id_get = $request->user_id;

        $user = User::find($user_id_get);

        if(!$user)
        {
            $response = [
                'status' => true,
                'data'    => '',
                'message' => 'Invalid account.',
            ];

            return response()->json($response, 200);
        }

        if($request->verification_code != $user->otp)
        {
           // return $this->sendError(__('Invalid verification code.'),'Invalid verification code.',200);

             $response = [
                'status' => true,
                'data'    => '',
                'message' => 'Invalid verification code.',
            ];

            return response()->json($response, 200);
        }
        
        $user->password = bcrypt($request->new_password);
        $user->otp = null;
        $user->save();

        /*** Send password reset success notification by email **/
        $user->sendPasswordResetSuccessNotification($user, "api");

        return $this->sendResponse(null, __('Password reset successfully.'),200);
    }


    public function default_profile_picture_get(Request $request){

            $getdefault_profile_picture = Default_profile_picture::where('status','active')->orderBy('id', 'desc')->get();

            $user = $request->user();

           // dd($user->default_img_id);
            $pictureData = array();
            foreach ($getdefault_profile_picture as  $picturevalue) {

                $ll = $picturevalue;

                if($user->default_img_id == $picturevalue->id){

                    $ll['user_selected'] = 1;
                }else{

                    $ll['user_selected'] = 0;
                }

                
                if($ll['image_name'] != '')
                {
                    $ll['image_name'] = url('uploads/default_profile_picture')."/".$ll['image_name'];
                }else{
                    $ll['image_name'] = '';
                }
               
                $pictureData[] = $ll;
            }

            return response()->json([
                'status'=>true,
                'data' => $pictureData,
                'message' => 'Default profile picture get successfully.!'
            ],200);
    }

}
