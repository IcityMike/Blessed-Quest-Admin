<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\ClientBeneficiaries;
use App\Models\SupportTicket;
use App\Models\CurrencySetting;
use App\Models\Settings;
use App\Models\Types_of_voice;
use App\Models\Notifications;
use App\Helpers\Common;
use App\Models\Subscription;
use App\Models\UserDevice;
use App\Models\Subscription_user;
use App\Models\User_notifications_settings;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\NotifyUpdateEmail_reminders_credits;
use App\Notifications\NotifyUpdateEmail_reminders_subscription;
use Carbon\Carbon;
use DB;
use File;

class CommonDetailController extends Controller
{
    public function __construct()
    {
        
    }

    protected function setData($value)
    {
        array_walk_recursive($value, function (&$item, $key) {
            $item = null === $item ? '' : $item;
        });
        return $value;;
    }

    public function GetVoiceTypeList(Request $request)
    {
        $typeOf_list = Types_of_voice::where('status','active')->get();
    
        return response()->json([
            'status'=>true,
            'message' => 'Type voice list.',
            'data' => @$typeOf_list,
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

    /***
    *   Developed by: Dhruvish suthar
    *   Description:  Receive reminders 3 days before my credits are about to expire
    ***/
    public function reminders_before_credits_expire_cron(Request $request){

       $today_date = date("Y-m-d");

       $Subscription_get = Subscription_user::where('status','active')->whereDate('end_date', date('Y-m-d', strtotime("3 days")))->get();

        foreach ($Subscription_get as $key => $subscriptionvalue) {

            $user_details_get = UserDevice::where('user_id',@$subscriptionvalue->user_id)->first();

            $user_notifications_settings = User_notifications_settings::where('user_id',@$subscriptionvalue->user_id)->first();

            $userData = User::find(@$subscriptionvalue->user_id)->first();

            if(@$user_notifications_settings->receive_reminders_days_before_credits_expire_email == '1'){

                $userData->notify(new NotifyUpdateEmail_reminders_credits('user', @$userData));
            }

            $store_user_noti = Notifications::Create([
                'user_id' => @$subscriptionvalue->user_id,
                'notification_title' => "Reminders your credits expire",
                'notification_details' => "Reminders your credits expire date is :-" .date('m-d-Y', strtotime("3 days")),
                'notification_type' => 'subscription',
            ]);

                if($user_details_get){

                    try {
                            if(@$user_notifications_settings->reminders_credits_expire == '1'){

                                 $get_fcm_acc_token = Common::FCM_token_new_get();

                                if($user_details_get->device_token){

                                    $to = $user_details_get->device_token;

                                    $url = 'https://fcm.googleapis.com/v1/projects/test-101b0/messages:send';

                                    $data = [
                                        'message' => [
                                          'token' => $to,
                                            'notification' => [
                                               // 'title' => @$this->data['title'],
                                                'title' =>"Reminders your credits expire",
                                                'body' => "Reminders your credits expire date is :-" .date('m-d-Y', strtotime("3 days")),
                                            ],
                                            "data" => [
                                                       // 'title'=> @$this->data['header'] ?? '',
                                                        'title'=> "Reminders your credits expire",
                                                        'sub_title'=>"Reminders your credits expire",
                                                        'body'=> "Reminders your credits expire date is :-" .date('m-d-Y', strtotime("3 days")),
                                                        'sound' => 'default',
                                                        'notification_type' => 'subscription',
                                                    ],
                                        ],
                                    ];

                                    $headers = [
                                        'Authorization: Bearer ' . $get_fcm_acc_token,
                                        'Content-Type: application/json',
                                    ];

                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                                    $response = curl_exec($ch);
                                    curl_close($ch);
                                    $response_arr = json_decode($response);

                                    print_r($response_arr);
                                    // dd($response_arr,$to,$this->device_token['0']);
                                   // Log::info( collect($response)->toArray());
                                }
                            }
                            // Log::info(" fhgfhjhfhfhfgh!");
                        } catch (Exception $e) {
                                Log::info( $e);
                        }
                    }
        }
    }


    /***
    *   Developed by: Dhruvish suthar
    *   Description:  Receive reminders 3 days before subscription auto-renews
    ***/
    public function reminders_before_subscription_auto_renews_cron(Request $request){

       $today_date = date("Y-m-d");

       $Subscription_get = Subscription_user::where('status','active')->whereDate('end_date', date('Y-m-d', strtotime("3 days")))->get();

            foreach ($Subscription_get as $key => $subscriptionvalue) {

                $user_details_get = UserDevice::where('user_id',$subscriptionvalue->user_id)->first();

                $userData = User::find(@$subscriptionvalue->user_id)->first();

                $user_notifications_settings = User_notifications_settings::where('user_id',@$subscriptionvalue->user_id)->first();

                if(@$user_notifications_settings->reminders_sub_autorenews_email == '1'){

                    $userData->notify(new NotifyUpdateEmail_reminders_subscription('user', $userData));
                }

                $store_user_noti = Notifications::Create([
                    'user_id' => @$subscriptionvalue->user_id,
                    'notification_title' => "Reminders your subscription",
                    'notification_details' => "Reminders your subscription renewal date is :-" .date('m-d-Y', strtotime("3 days")),
                    'notification_type' => 'subscription',
                ]);

                if($user_details_get){

                    try {

                            if(@$user_notifications_settings->reminders_sub_autorenews == '1'){

                                $get_fcm_acc_token = Common::FCM_token_new_get();

                                //dd($get_fcm_acc_token);

                                //dd($this->data['taskId'],$this->data['taskType'],$this->data['taskTime'],$this->data['header'],$this->data['title'],$this->data['text']);
                                ///////// send push code
                               //  $to = 'fdI_-bRvQHiQLhkWhR9bKZ:APA91bGewIOJVYNUndb_SxrTpfRGqt28V72oFj-12mJbwTgZEWeZVi9jX7R7dlFoqwUD7m1hhbIsOhGs_5hJqaanCbTwKmcSuzH46YfYwv9sMKz_RUkOLvTfKcytXyYE5HXHNaWadAl-';
                                //dd($subscriptionvalue);
                                if($user_details_get->device_token){

                                    $to = $user_details_get->device_token;

                                    $url = 'https://fcm.googleapis.com/v1/projects/test-101b0/messages:send';

                                    $data = [
                                        'message' => [
                                      'token' => $to,
                                        'notification' => [
                                             // 'title' => @$this->data['title'],
                                            'title' =>"Reminders your subscription",
                                            'body' => "Reminders your subscription renewal date is :-" .date('m-d-Y', strtotime("3 days")),
                                        ],
                                        "data" => [
                                                    // 'title'=> @$this->data['header'] ?? '',
                                                    'title'=> "Reminders your subscription",
                                                    'sub_title'=> "Reminders your subscription",
                                                    'body'=> "Reminders your subscription renewal date is :-". date('m-d-Y', strtotime("3 days")),
                                                    'notification_type' => 'subscription',
                                                ],
                                        ],
                                    ];

                                    $headers = [
                                    'Authorization: Bearer ' . $get_fcm_acc_token,
                                    'Content-Type: application/json',
                                    ];

                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_POST, 1);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                                    $response = curl_exec($ch);
                                    curl_close($ch);

                                    $response_arr = json_decode($response);

                                    print_r($response_arr);
                                   // print_r(date('Y-m-d', strtotime("3 days")));
                                    // dd($response_arr,$to,$this->device_token['0']);
                                   // Log::info( collect($response)->toArray());
                                }
                            }
                        // Log::info(" fhgfhjhfhfhfgh!");
                        } catch (Exception $e) {
                                Log::info( $e);
                        }
                }
            }
    }

}           
