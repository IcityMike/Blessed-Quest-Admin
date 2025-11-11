<?php

namespace App\Traits;
use Storage;
use Edujugon\PushNotification\PushNotification;
use Twilio\Rest\Client;
use App\Models\UserDevice;
use App\Models\User;
use Exception;
use DB;
use Log;

trait UserPushNotificationTrait {

    public function sendUserPushTrait($data)
    {
        $user = User::find($data['user_id']);

        $this->data['user_id']=$data['user_id'] ?? '';
        $this->data['associated_id']= $data['associated_id'] ?? '';
        $this->data['insurance_type']= $data['insurance_type'] ?? '';
        
        $this->data['title']=$data['title'] ?? '';
        $this->data['text']=$data['text'] ?? '';
        $this->data['type']=$data['type'] ?? '';
        $this->data['notificationType']=$data['notificationType'] ?? '';

        $this->data['userName'] = $data['userName'] ?? '';
        $this->data['password'] = $data['password'] ?? '';


        $this->devicePlateform=[];
        $this->device_token="";

        $this->deviceAndroid= UserDevice::select('device_token')
        ->where('user_id',$this->data['user_id'])
        ->where('type','android')
        ->get()
        ->pluck('device_token')
        ->toArray();

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
        // if($user && $user->status != "block"){
            if(count($this->deviceIos)){
                $this->device_token=$this->deviceIos;
                foreach($this->deviceIos as $token){
                    $this->device_token = $token;
                    $this->sendIosNotificationClientTrait();
                }
            }
        // }
        
        // send sms to client using twilio
        // if($user){
        //     $phone_number = ($user->phone_number) ? $user->phone_number : $user->phone_number2;
        //     $this->sendSMSToClient($phone_number);
        // }

        // send login detail to client using twilio
        if($this->data['notificationType'] == "login_details"){
            if($user){
                $phone_number = ($user->phone_number) ? $user->phone_number : $user->phone_number2;
                $this->sendCredentialsToClient($phone_number);
            }
        }
        
    }

    public function sendSMSToClient($phone_number){
        // $receiverNumber = "+61426659349";

        $receiverNumber = "+".$phone_number;
        // $message = $this->data['title'].' of '.$this->data['insurance_type'];
        $message = $this->data['title'];
        // dd($message);
        try {
            $account_sid = env('TWILIO_SID');
            $auth_token = env('TWILIO_TOKEN');
            $twilio_number = env('TWILIO_FROM');
            $client = new Client($account_sid, $auth_token);

            $res = $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message
            ]);
  
            \Log::info($res." SMS sent to client");
  
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }


    public function sendCredentialsToClient($phone_number){
        $receiverNumber = "+".$phone_number;
        $message = "User Name: ".$this->data['userName']." Password: ".$this->data['password'];
        try {
            $account_sid = env('TWILIO_SID');
            $auth_token = env('TWILIO_TOKEN');
            $twilio_number = env('TWILIO_FROM');
            $client = new Client($account_sid, $auth_token);
            
            $res = $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message
            ]);
  
            \Log::info($res." SMS sent to client");
  
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }


    public function sendAndroidNotificationClientTrait(){
        Log::info(env('PUSHER_APP_KEY_CUSTOME'),$this->device_token);
            // dd($this->data['notificationType'],$this->device_token,env('PUSHER_APP_KEY_CUSTOME'));
        try {
            $push = new PushNotification('fcm');

            $response   =   $push->setMessage([
        
            'notification' => [
                // 'content-available' => ($this->data['notificationType'] == "block_user") ? 1 :'',
                'associated_id' => $this->data['associated_id'] ?? '',
                'title'=> $this->data['title'] ?? '',
                'body'=> $this->data['text'] ?? '',
                'sound' => 'default',
                'text' => $this->data['text'] ?? '',
                'type' => $this->data['notificationType']
                ]
            ])
            ->setApiKey(env('PUSHER_APP_KEY_CUSTOME'))
            ->setDevicesToken($this->device_token)
            // ->setApiKey('eSQMV6nwQk-2cGZk5hJcAG:APA91bHgDJ2VQ_BLH6tSLr4XtYv0KD0nocF4C-JFoTF7uXBsR_ryclzq9KrxIZUPy0uHokn23gJik1uNCNLSiBOHw9RBXpI0M8e3T0LyslPquD208YLTQci76pwOgxxQuQp57tkMozvE')
            // ->setDevicesToken('eSQMV6nwQk-2cGZk5hJcAG:APA91bHgDJ2VQ_BLH6tSLr4XtYv0KD0nocF4C-JFoTF7uXBsR_ryclzq9KrxIZUPy0uHokn23gJik1uNCNLSiBOHw9RBXpI0M8e3T0LyslPquD208YLTQci76pwOgxxQuQp57tkMozvE')
            ->send()
            ->getFeedback();

            // dd($response);
            Log::info( collect($response)->toArray());

        } catch (Exception $e) {
            Log::info( $e);
        }
    }

    // public function sendIosNotificationClientTrait(){
    //     try {
    //         $push = new PushNotification('apn');
    //         $response  =  $push->setMessage([
    //         'aps' => [
    //             'alert'=> $this->data['title'] ?? '',
    //             'sound' => 'default',
    //             'badge' =>0,
    //         ],
    //         'extraPayLoad' => [
    //             'text' => $this->data['text'] ?? '',
    //             'type' => $this->data['notificationType'] ?? ""
    //         ]
    //     ])
    //     ->setConfig([
    //         'passPhrase' => env('GI_IOS_CERTIFICATE_PASSWORD'),
    //         'certificate' =>config_path(). "/iosCertificates/".env('GI_IOS_CERTIFICATE_NAME')
    //     ])
    //     ->setDevicesToken($this->device_token)
    //     ->send()
    //     ->getFeedback();

    //     Log::info( collect($response)->toArray());

    //     } catch (Exception $e) {
    //         Log::info($e);
    //     }
    // }


    public function sendIosNotificationClientTrait($type=''){

        $http2ch = curl_init();
        curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);

        // send push
        $apple_cert = '';
        
        // $message = '{"aps":{"alert":"'.$this->data['title'].'","sound":"default","content-available" : 1}}';

        $message = '{
            "aps":{"alert":"'.$this->data['title'].'","sound":"default","content-available" : 1},
            "extraPayLoad":{"text":"'.$this->data['text'] ?? ''.'","type":"'.$this->data['notificationType'] ?? "".'"}
            }';


        // $token =  '8f899ca1ad588f456c9ff32abbf3aa4b2e58e4ef8c546589a4537a7f769da95e';
        $token =  $this->device_token;
        $type = "development";
        if(!empty($type) && $type == 'production'){
            $http2_server = 'https://api.push.apple.com'; // or 'api.push.apple.com' if production
        }else{
            $http2_server = 'https://api.sandbox.push.apple.com'; // or 'api.push.apple.com' if development
        }

        // $app_bundle_id = 'com.jonathan.GigBook';
        $app_bundle_id = '';

        $status = $this->sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token);

        // echo "Response from apple -> {$status}\n";
        Log::info( collect($status)->toArray());
    }



    function sendHTTP2Push($http2ch, $http2_server, $apple_cert,   $app_bundle_id, $message, $token) {
        //////////////////////////////////////////////
        // @param $http2ch          the curl connection
        // @param $http2_server     the Apple server url
        // @param $apple_cert       the path to the certificate
        // @param $app_bundle_id    the app bundle id
        // @param $message          the payload to send (JSON)
        // @param $token            the token of the device
        // @return mixed            the status code
        ///////////////////////////////////////////////

        // url (endpoint)
        $url = "{$http2_server}/3/device/{$token}";


        // certificate
        // $cert = base_path().'/'.$apple_cert;
        $cert = config_path().'/iosCertificates/'.$apple_cert;

        // echo $cert;die;
        // headers
        $headers = array(
            "apns-topic: {$app_bundle_id}",
            "User-Agent: My Sender"
        );

        // other curl options
        curl_setopt_array($http2ch, array(
            CURLOPT_URL => $url,
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $message,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLCERT => $cert,
            CURLOPT_SSLCERTPASSWD => '123456',
            CURLOPT_HEADER => 1
        ));

        // go...
        $result = curl_exec($http2ch);

        if ($result === FALSE) {
          print_r("Curl failed: " .  curl_error($http2ch));die;
        }

        // get response
        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);

        return $status;
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
