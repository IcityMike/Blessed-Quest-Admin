<?php

namespace App\Http\Controllers\API;

use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Edujugon\PushNotification\PushNotification;
use App\Traits\UserPushNotificationTrait;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use App\Helpers\Common;
use App\Models\User;
use Carbon\Carbon;
use Auth;
use Str;
use Validator;
use Exception;
use DB;
use Log;

class DeviceController extends Controller
{
    use UserPushNotificationTrait;
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

    public function registerDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'device_id' => 'required',
            'device_token' => 'required',
            'type' => 'required|in:android,ios',
        ], [
            'user_id.exists' => 'inValid User id',
            'type.in' => 'Device type is either android or ios'
        ]);

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }

        $input = $request->except(['_token']);
        $input['user_id'] = $request->user_id;

        $device = UserDevice::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'device_id' => $request->device_id,
                'device_token' =>$request->device_token,
                'type' => $request->type,
            ],
            [
                'user_id' =>$request->user_id,
                'device_id' =>$request->device_id
            ]
        );



        // $device = UserDevice::wherePatientId($request->user_id)->first();

        // if (!$device) {
        //     $device = UserDevice::create($input);
        // } else {
        //     $device->update($input);
        // }
        $success['device'] = $device;
        return $this->sendResponse($success, 'Device Registered Successfully.');
    }

    public function unregisterDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'device_id'=> 'required',
        ], 
        [
            'user_id.exists' => __('api_messages.invalidUser'),
        ]);

        if($validator->fails())
        {
            return $this->sendError(__('There are errors in your input data.'),$validator->errors(),200);
        }
      
        UserDevice::where('user_id',$request->user_id)->where('device_id',$request->device_id)->delete();

        return $this->sendResponse(null, "Logged out successfully.");
    }

    public function sendNotification(Request $request){
        try {
            $push = new PushNotification('fcm');
            $response   =   $push->setMessage([
            'data' => [
                    'title'=> "test",
                    'body'=>"test",
                    'sound' => 'default',
                    'text' => "test",
                    'type' =>'type'
                    
                    ]
            ])
            ->setApiKey("")
            ->setDevicesToken("")
            ->send()
            ->getFeedback();


            Log::info( collect($response)->toArray());


            } catch (Exception $e) {
                Log::info($e);
        }
    }
}
