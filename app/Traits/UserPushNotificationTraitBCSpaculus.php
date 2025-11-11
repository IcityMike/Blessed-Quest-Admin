<?php

namespace App\Traits;
use Storage;
use Edujugon\PushNotification\PushNotification;
use App\Models\UserDevice;
use Exception;
use DB;
use Log;

trait UserPushNotificationTrait {

    public function sendUserPushTrait($data)
    {
        $this->data['user_id']=$data['user_id'];
        $this->data['title']=$data['title'];
        $this->data['text']=$data['text'];
        $this->data['type']=$data['type'];
        $this->data['notificationType']=$data['notificationType'];

        $this->devicePlateform=[];
        $this->device_token="";
        
        $this->deviceAndroid= UserDevice::select('device_token')
        ->where('user_id',$this->data['user_id'])
        ->where('type','android')
        ->get()
        ->pluck('device_token')
        ->toArray();
        
        // dd($this->deviceAndroid);

        $this->deviceIos= UserDevice::select('device_token')
        ->where('user_id',$this->data['user_id'])
        ->where('type','ios')
        ->get()
        ->pluck('device_token')
        ->toArray();

        //send for android device
        if(count($this->deviceAndroid)){
            $this->device_token= $this->deviceAndroid;
            $this->sendAndroidNotificationClientTrait();
        }

         //send for ios device
        if(count($this->deviceIos)){
            $this->device_token=$this->deviceIos;
            $this->sendIosNotificationClientTrait();
        }
    }


    public function sendAndroidNotificationClientTrait(){

        try {
            $push = new PushNotification('fcm');
            $response   =   $push->setMessage([
        // 'notification' => [
        //         'title'=>'My Appointment',
        //         'body'=> $this->request->text ?? '',
        //         'sound' => 'default'
        //         ],
        'data' => [
                'title'=> $this->data['title'] ?? '',
                'body'=> $this->data['text'] ?? '',
                'sound' => 'default',
                // 'practitioner_id' =>$this->data['practitioner_id'] ?? '',
                'text' => $this->data['text'] ?? '',
                'type' =>$this->data['notificationType']
                
                ]
        ])
        ->setApiKey(env('PUSHER_APP_KEY_CUSTOME'))
        ->setDevicesToken($this->device_token)
        ->send()
        ->getFeedback();


        Log::info( collect($response)->toArray());

        } catch (Exception $e) {
            Log::info( $e);
    }

    }

    public function sendIosNotificationClientTrait(){
        try {
            $push = new PushNotification('apn');
            $response  =  $push->setMessage([
            'aps' => [
                'alert'=> $this->data['title'] ?? '',
                'sound' => 'default',
                'badge' => 0,
            ],
            'extraPayLoad' => [
                'text' => $this->data['text'] ?? '',
                'type' => $this->data['notificationType'] ?? ""
            ]
        ])
        ->setConfig([
            'passPhrase' => env('GI_IOS_CERTIFICATE_PASSWORD'),
            'certificate' =>config_path(). "/iosCertificates/".env('GI_IOS_CERTIFICATE_NAME')
        ])
        // ->setApiKey(env('PUSHER_APP_KEY_CUSTOME'))
        ->setDevicesToken($this->device_token)
        ->send()
        ->getFeedback();
        
        Log::info( collect($response)->toArray());

        } catch (Exception $e) {
            Log::info($e);
        }
    }

    function super_unique($array,$key)
    {
        $temp_array = [];
        foreach ($array as &$v) {
            if (!isset($temp_array[$v[$key]]))
            $temp_array[$v[$key]] =& $v;
        }
        $array = array_values($temp_array);
        return $array;

    }


 



}
