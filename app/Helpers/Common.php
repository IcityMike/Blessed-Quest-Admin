<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\ExpressCheckout;
use GuzzleHttp\Exception\RequestException;

use App\Models\ReferralClientPartnersCommissions;
use App\Models\AdminRoleModulePermission;
use App\Models\CurrencySetting;
use App\Models\ReferralPartner;
use App\Models\Librarys;
use App\Models\Librarys_audio;
use App\Models\Transaction;
use App\Models\User_notifications_settings;
use App\Models\Settings;
use App\Models\UserDevice;
use App\Models\Events_user;
use App\Models\Notifications;
use Twilio\Rest\Client;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\NotifyUpdate_account_user;
use Carbon\Carbon;
use DateTimeZone;
use DateTime;
use Log;
use URL;
use PDF;

class Common
{

	public static function priceFormat($value)
	{
		$string = floatval($value);
		$formatted = number_format($string, 2, '.', '');
		return $formatted;
	}

	public static function getWebsiteSettings()
	{
		
		return Settings::first();
	}
	
	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get website logo
    ***/
	public static function getWebsiteLogo()
	{
		
		$settings = Settings::first();
		// $logo = $settings->site_logo ? url(config('settings.site_logo_folder'))."/".$settings->site_logo : url('public/files/logo.png');
		$logo = $settings->site_logo ? url(config('settings.site_logo_folder'))."/".$settings->site_logo : url('public/files/logo.png');
		
		return $logo;
	}


	public static function getEventUser($user_id)
	{

		$userProfileData = User::where('id',$user_id)->first();
		$events_user = Events_user::select('id','library_id','event_name','event_id')->where('user_id',$user_id)->orderBy('id', 'desc')->get();

        $userData = array();
        foreach ($events_user as  $value) {

            $librarys_user = Librarys::where('id',$value->library_id)->where('status','active')->first();

            // 28-01-2025 $librarys_audioget = Librarys_audio::where('voice_type',$userProfileData->type_of_voice)->where('status','active')->first();

            $librarys_audioget = Librarys_audio::where('voice_type',@$userProfileData->type_of_voice)->where('library_id',@$librarys_user->id)->where('status','active')->first();

                 $ll = $value;
                if(@$librarys_audioget->mp3_file_name != '')
                {
                    $ll['user_song'] = url('uploads/mp3')."/".$librarys_audioget->mp3_file_name;

                }else{

                    $ll['user_song'] = '';
                }

             $ll['event_id'] = @$value->event_id;
             $ll['voice_type'] = @$librarys_audioget->voice_type;
             $ll['library_name'] = @$librarys_user->name;
             $ll['library_id'] = @$librarys_user->id;
           
            $userData[] = $ll;
        }

        return $userData;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get website favicon
    ***/
	public static function getWebsiteFavicon()
	{
		
		$settings = Settings::first();
		$siteFavicon = $settings->site_favicon ? url(config('settings.site_favicon_folder'))."/".$settings->site_favicon : url('public/files/logo-small.png');
		
		return $siteFavicon;
	}


	public static function imgURL_get($imgUrl,$imgFolder){

	//dd($imgUrl,$imgFolder);
			$imageUrl = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=250&?maxheight=250&photoreference='.$imgUrl.'&key=AIzaSyB-hYfoNr_5ih_LIrP0kfmfZVNhfdCMNuY';                        
	        // Initialize cURL session
	        $ch = curl_init($imageUrl);
	        // Set cURL options
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response as a string
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  // Follow redirects if needed
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (if needed)
	        $imageContent = @file_get_contents($imageUrl);

	        if ($imageContent !== false) {
	           
	            $imagePath = rand(10,100)."-".$imgFolder."-".time().".png";

	            //dd($imagePath);

	           // $d =  Storage::disk('Blessed_location_imgs')->put($imagePath, $imageContent);
	            //$path = public_path() . "/storage/".$imgFolder."/" . $imagePath;
	          //  $d = file_put_contents($path, $imagePath);
	            
	            $path = public_path() . "/uploads/".$imgFolder."/" . $imagePath;
	            $d = file_put_contents($path, $imageContent);

	            return $imagePath;
	        } else {
	            
	           return $imagePath = '';
	        }
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get User's profile picture
    ***/
	public static function getUserPicture()
	{
		
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$admin = Admin::find(Auth::guard('admin')->user()->id);
			$profilePicture = $admin->profile_picture ? url(config('settings.admin_picture_folder'))."/".$admin->profile_picture : url(config('settings.default_picture'));
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$client = User::find(Auth::guard('client')->user()->id);
			$profilePicture = $client->profile_picture ? url(config('settings.client_picture_folder'))."/".$client->profile_picture : url(config('settings.default_picture'));

		}
		else if(Auth::guard('refferal')->check() && request()->route()->getPrefix() == config('settings.route_prefix.refferal'))
		{
			$referral = ReferralPartner::find(Auth::guard('refferal')->user()->id);
			$profilePicture = $referral->profile_picture ? url(config('settings.refferal_partner_folder'))."/".$referral->profile_picture : url(config('settings.default_picture'));

		}
		// else if(Auth::guard('employee')->check() && request()->route()->getPrefix() == config('settings.route_prefix.employee'))
		// {
		// 	$employee = Employee::find(Auth::guard('employee')->user()->id);
		// 	$profilePicture = $employee->profile_picture ? url(config('settings.employee_picture_folder'))."/".$employee->profile_picture : url(config('settings.default_picture'));

		// }
		else
		{
			$client = User::find(Auth::guard('client')->user()->id);
			$profilePicture = $client->profile_picture ? url(config('settings.client_picture_folder'))."/".$client->profile_picture : url(config('settings.default_picture'));
			//$profilePicture = url(config('settings.default_picture'));
		}
		return $profilePicture;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get user vise dashboard link
    ***/

    public static function getDashboardLink()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			if(Auth::guard('admin')->user()->role->default_dashboard == "admin")
			{
				$route = route('admin.dashboard');
			}
			else{
				$route = "#";
			}
			
		}	
		else
		{
			$route = "#";
		}	
		return $route;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get user vise notifications link
    ***/

    public static function getNotificationURL()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$route = route('admin.notifications');
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$route = route('client.notifications');
		}
		else
		{
			$route = "#";
		}
		return $route;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get user vise other notifications link
    ***/

    public static function getOtherNotificationUrl()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$route = route('admin.otherNotifications');
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$route = route('client.otherNotifications');
		}
		else
		{
			$route = "#";
		}
		return $route;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get user type
    ***/

    public static function getUserType()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			// $type = config('settings.route_prefix.admin');
			$type = config('settings.route_prefix.admin');
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$type = config('settings.route_prefix.client');
		}
		else
		{
			$type = null;
		}
		return $type;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get user vise edit profile link
    ***/

    public static function getEditProfileLink()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$route = route('admin.editProfile');
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$route = route('client.editGeneralProfile');
		}
		else if(Auth::guard('refferal')->check() && request()->route()->getPrefix() == config('settings.route_prefix.refferal'))
		{
			$route = route('refferal.editProfile');
		}
		else
		{
			$route = "#";
		}
		return $route;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get change password link
    ***/

    public static function getChangePasswordLink()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$route = route('admin.changePassword');
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$route = route('client.changePassword');
		}
		else if(Auth::guard('refferal')->check() && request()->route()->getPrefix() == config('settings.route_prefix.refferal'))
		{
			$route = route('refferal.changePassword');
		}
		else
		{
			$route = "#";
		}
		return $route;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get user vise log out link
    ***/

    public static function getLogoutLink()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$route = route('admin.logout');
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$route = route('client.logout');
		}
		else if(Auth::guard('refferal')->check() && request()->route()->getPrefix() == config('settings.route_prefix.refferal'))
		{
			$route = route('refferal.logout');
		}
		else
		{
			$route = "#";
		}
		return $route;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get user's name
    ***/
	public static function getUserName()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$name = Auth::guard('admin')->user()->first_name;
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			// $name =Auth::guard('client')->user()->first_name;
			$name =Auth::guard('client')->user()->name;
		}
		else if(Auth::guard('refferal')->check() && request()->route()->getPrefix() == config('settings.route_prefix.refferal'))
		{
			$name = Auth::guard('refferal')->user()->first_name;
		}
		else
		{
			$name = "Unknown";
		}
		return $name;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get user's email
    ***/
	public static function getUserEmail()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$name = Auth::guard('admin')->user()->email;
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$name =Auth::guard('client')->user()->email;
		}
		else if(Auth::guard('refferal')->check() && request()->route()->getPrefix() == config('settings.route_prefix.refferal'))
		{
			$name = Auth::guard('refferal')->user()->email;
		}
		else
		{
			$name = "Unknown";
		}
		return $name;
	}

	 /***
    *   Developed by: Radhika Savaliya
    *   Description: Convert PST datetime to our local format
    ***/
    public static function convertPSTToOurs($date)
    {
   
		$date = new DateTime($date);
		$date->setTimezone(new DateTimeZone(config('app.timezone')));
      	return $date->format("d/m/Y");
     
	}
	
	 /***
    *   Developed by: Radhika Savaliya
    *   Description: Convert PST datetime to our local format with time
    ***/
    public static function convertPSTToOursWithTime($date)
    {
     
		$date = new DateTime($date);
		$date->setTimezone(new DateTimeZone(config('app.timezone')));
      	return $date->format("Y-m-d H:i:s");
	}

 	/***
    *   Developed by: Radhika Savaliya
    *   Description: Activity log
    ***/
	public static function logActivity($model, $user, $properties, $comments)
	{

		$log = activity()
			->performedOn($model)
			->causedBy($user)
			->withProperties($properties)
			->log($comments);
		
		return true;
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: POST API Request
    ***/
	public static function postRequest($url, $request, $json = false, $auth = null)
    {
		try
		{
			$client = new \GuzzleHttp\Client();
			if($json)
			{
				$requestData = [
					'json' => $request
				];
				
			}
			else
			{
				$requestData = [
					'form_params' => $request
				];
				
			}
			if($auth)
			{
				$headers = ['auth' => $auth];
			}
			else
				$headers = null;
			
			$response = $client->request('POST', $url, $requestData );
			
			$response = $response->getBody()->getContents();
			
			return $response;
		}		
		catch (RequestException $e) {
			var_dump($e->getResponse()->getBody()->getContents()); die;
			
		
		} catch (\Exception $e) {
			echo $e->getMessage(); die;
			
		
		}
	}
	
	/***
    *   Developed by: Radhika Savaliya
    *   Description: GET API Request
    ***/
	public static function getRequest($url, $auth = null)
    {
		$client = new \GuzzleHttp\Client();
		if($auth)
		{
			$headers = ['auth' => $auth];
		}
		else
			$headers = null;
        $request = $client->get($url, $headers);
        $response = $request->getBody()->getContents();
        return $response;
	}
	
	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get client login link
    ***/
	public static function getClientForgotPasswordLink()
    {
		return route('client.loginindex');
	}
	
	public static function getUser()
	{
		if(session('loginType') == "admin")
			return Auth::guard('admin')->user();
		else if(session('loginType') == "refferal")
			return Auth::guard('refferal')->user();
		else
			return null;	
	}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Check if logged in admin user has create permission for provided module or not
    ***/
	public static function hasPermission($moduleId, $permissionId)
	{
		if (Auth::guard('admin')->user()->type === "super" && Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin')) {
	        $accessible = true;
	        
	    } else {
			
			$accessible = AdminRoleModulePermission::whereRoleId(Auth::guard('admin')->user()->role_id)->whereHas('module_permission',function($q) use($moduleId, $permissionId){
				$q->whereModuleId($moduleId)->wherePermissionId($permissionId);
			})->first();

			
		}
		return $accessible;
}

	/***
    *   Developed by: Radhika Savaliya
    *   Description: Get list of unread other notifications for logged in user
    ***/
	public static function getOtherNotificationList()
    {
		
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$notifications = OtherNotification::getAdminNotifications(10);
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			$notifications = OtherNotification::getClientNotifications(null,10);
		}
		else{
			$notifications = [];
		}
      
       	return $notifications;  
    }

	/*   Developed by: Radhika Savaliya
    *   Description: Get list of unread other notifications for logged in user
    ***/
	public static function getOtherNotificationCount()
	{
		if(Auth::guard('admin')->check() && request()->route()->getPrefix() == config('settings.route_prefix.admin'))
		{
			$notificationsCount = OtherNotification::where('user_type','A')->where('user_id',Auth::guard('admin')->user()->id)->whereNull('read_at')->count();
		}
		else if(Auth::guard('client')->check() && request()->route()->getPrefix() == config('settings.route_prefix.client'))
		{
			
			$notificationsCount = OtherNotification::where('user_type','C')->where('user_id',Auth::guard('client')->user()->id)->whereNull('read_at')->count();
		}
        else{
			$notificationsCount = 0;
        }
        // dd($notificationsCount);
       return $notificationsCount;
	}

	// get flags https://github.com/yusufshakeel/mysql-country-with-flag/blob/master/country.sql
	public static function getFlag()
	{
		return [
			'AUD'  =>  	URL::asset('/flags/au.png'),
			'BDT'  =>  	URL::asset('/flags/bd.png'),
			'CAD'  => 	URL::asset('/flags/ca.png'),
			'EUR'  => 	URL::asset('/flags/de.png'),
			'GBP'  => 	URL::asset('/flags/gb.png'),
			'HKB'  => 	URL::asset('/flags/ht.png'),
			'INR'  => 	URL::asset('/flags/in.png'),
			'USD'  => 	URL::asset('/flags/us.png'),
			'PKR'  => 	URL::asset('/flags/pk.png'),
			'NPR'  => 	URL::asset('/flags/np.png'),
			'LKR'  => 	URL::asset('/flags/lk.png'),
			'DUMMY'  => URL::asset('/flags/02.png'),
		];
	
	}

	public static function getTransactionStatusColor()
	{
		return [
			'IN_PROCESS' => '#F8B834',
			'INITIATE' => '#FF6E12',
			'PAID' => '#8fce00',
			'CANCELLED' => '#f44336',
			'FAILED' => '#F92604',
			'AWAITING_FUNDS' => '#FF6E12',
			'INFO_REQUESTED' => '#FF6E12',
			'COMPLIANCE_REJECTED' => '#8fce00',
			'SENT_TO_BANK' => '#F92604',
			'PG_PROCESSING' => '#F92604',
			'RETURNED' => '#F92604',
			'REJECTED' => '#F92604',
			'CANCELLED' => '#F92604',
			'PAYMENT_RETURN' => '#3DB554',
		];
	}

	public static function sendSMSToClient($sms_message,$receiverNumber)
	{
        // $receiverNumber = "";
		if(!empty($receiverNumber))
		{
			try {
	            $account_sid = config('settings.TWILIO_SID');
	            $auth_token = config('settings.TWILIO_TOKEN');
	            $twilio_number = config('settings.TWILIO_FROM');
	            $client = new Client($account_sid, $auth_token);
	            $res = $client->messages->create($receiverNumber, [
	                'from' => $twilio_number, 
	                'body' => $sms_message
	            ]);

	            \Log::info($res." SMS sent to client");
	        } catch (Exception $e) {

	            Log::info($e->getMessage());
	        }
		}
        
    }

    /***
    *   Developed by: dhruvish suthar
    *   Description: Activate subscription
    ***/
    public static function FCM_token_new_get(){

	        $pri_key = "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCxhNahAeBsAJVJ\np0VJkdoTw4KtWWP4XnhClnp37GNFhTbRHGWRNl5WssfeUI7p6Pks0UFxLLusWAc+\niqHhZeB5LW93dKfjPWpr0bXz2/wMttwmVW7f8pF96h2IqBKU9QUgGe+JIYVJYW4L\n0VL8s3XAt4bmeD2lyJF0AE77bOueZXvhntLArvBnqComuqzgtl5ib9zy4d3lBXd6\nEtkJnEXZusBnq9BzsFVEHbppSMi0eBTkySF7k/Z81HzKDRjMZdPDIwInm819ZPxa\n79f7eFV1GpPqMdXI3sLPN0IetA9GR5Amiu6LukEuqi28+Z2i1+0zjp3IicdILlPI\njYC12EQPAgMBAAECggEAHZby36ucaOQ//iLvna++FgVExvhbfY7hpNfIWi0A7xmh\nZYHCPE4/s1vhjOEIsVywDBRlQCQgtD2IF5isGxIMNKaqKdM8GNin4Ij4N0m33bat\nQ4aCELoyHjbj7V92mXWuAQfRsZ3wxaaD3Vxq5MxVGGZ/CfOI8xVXiWpzv90L+T7S\nJjuqhdIyOAD510nRbxYyBZVpgrEg5hHf/R5gYBkEa1kNhONgPFfWpPLdCqAQISmV\nXhcLO+xwFvSIj71f7KK+/roQMwef+iri3jZziCZhUyt3zK2C1hDHu1cK9S/54gpv\nS6q1Fqtzz4gCbVnOvHsTkjHFzodwJP8IhP3+R+i14QKBgQDc/drFisRbZY8z/xyI\nv4SOnTALMJoEyM+DZqZ5MCuHYeiN+w6M6WEd7O1g1wVfVSHHLtUYFT8xZJxY3mXQ\nYW6g57TBraJgWAJHmxuxo7rKX3b2bHOOJWR9Z842XkZbRin7GFD+4zRwRxl/sVyK\nTpr79ZHJ5jmy4QfGkhmmoHOvlQKBgQDNo/sm9DBSlvkkFJjH9qVdgiVQBbXoII0s\n/67MzzC1XeoseSeAehdHKfi3XmZnwAkA8k+ul100wF8lmfY392rJdeMTRNQkROiK\nWo9mBiSfA7drDnHk8Roln66jykvKo06TgapPnULsHNAYUCGGqudbmwnbeEPsvcjH\nDH4WIBpMEwKBgQCWyihyziL5cizqf9JKhNPANAVKPVl6lkLyGCLTYkjxUZ9K8dmV\n0NDRiI7Ymx8xU3d+37fLfQPngg0knmu+ah0AcdnmpcG8F5FGptGYvm2AFO+paYrX\nnTAaWbBdbKp5MUEH8imiIgnv6rO9a5oHTWd3MLLPnuYT5nRVy+7wwV7umQKBgQCK\nkt0U0+qYTRpYoPSPk3q994yzTxXU9dKMp0OgymH1ZfL+wynGXv++ud7bmmaBtZub\noq3lZiSt/pBfY0/TSQwR9Dnv6yrVwgW97ebCBpO5ACUipFpFv98xyVhDEXhfdgrm\nRbOB8OR+/a+RIrZk7Ff5mBFdbr6/EujwkU+KE1oFNwKBgBGfFeypdndy1nPq2Wv5\nvq0O+ZUPxx9FkjWSdVhsM7t2uWCdmJ9SmdKPklQThXyvzg71eBFHfUjk5MWTs0qM\nrd1UxlUNr3e32X7WRuHNnv88fz4S1A2diDbPNMYrQCb3vC4tm1Qpt13r/RsKxOfu\nD9g3mNMcg3R+jt3JrtfrYEax\n-----END PRIVATE KEY-----\n";

	        $payload = [
	            'iss' => 'firebase-adminsdk-b950p@test-101b0.iam.gserviceaccount.com',
	            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
	            'aud' => 'https://oauth2.googleapis.com/token',
	            'exp' => time() + 3600,
	            'iat' => time(),
	        ];

	        $jwtHeader = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
	        $jwtHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($jwtHeader));
	        $jwtPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

	        $signature = '';
	        openssl_sign($jwtHeader . '.' . $jwtPayload, $signature, $pri_key, OPENSSL_ALGO_SHA256);
	        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

	        $jwt = $jwtHeader.'.'. $jwtPayload.'.'.$base64UrlSignature;

	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL,'https://oauth2.googleapis.com/token');
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query([
	            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
	            'assertion' => $jwt,
	        ]));
	       
	        $response = curl_exec($ch);

	       // dd($ch);
	        curl_close($ch);

	        @$acc_token = json_decode(@$response, true)['access_token'];

	        return @$acc_token;

	}


	/***
    *   Developed by: Dhruvish suthar
    *   Description:  Receive reminders 3 days before subscription auto-renews
    ***/
    public static function common_for_user_notification($user_id){

       $today_date = date("Y-m-d");

        $user_details_get = UserDevice::where('user_id',$user_id)->first();

        $userData = User::find(@$user_id)->first();

        $user_notifications_settings = User_notifications_settings::where('user_id',@$user_id)->first();

        if(@$user_notifications_settings->notify_updates_changes_account_email == '1'){

        	$userData->notify(new NotifyUpdate_account_user('user', $userData));
        }

        $message = "Updates your account details";

        $store_user_noti = Notifications::Create([
		                'user_id' => @$user_id,
		                'notification_title' => "Account Changes",
		                'notification_details' => "Updates your account information",
		                'notification_type' => 'profile_update',
		            ]);

        if($user_details_get){

            try {
                
                if(@$user_notifications_settings->notify_updates_changes_account == '1'){
                 
                    $get_fcm_acc_token = Common::FCM_token_new_get();
                    if($user_details_get->device_token){

                        $to = $user_details_get->device_token;

                        $url = 'https://fcm.googleapis.com/v1/projects/test-101b0/messages:send';

                        $data = [
                            'message' => [
                          'token' => $to,
                            'notification' => [
                                'title' => "Account Changes",
                                'body' => "Updates your account information",
                            ],
                            "data" => [
                                        'title'=> "Account Changes",
                                        'sub_title'=> "Account Changes",
                                        'body'=> "Updates your account information",
                                        'sound' => 'default',
                                        'notification_type' => 'profile_update',
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
                        // dd($response_arr,$to,$this->device_token['0']);
                        Log::info( collect($response)->toArray());
                    }
                 }
                // Log::info(" fhgfhjhfhfhfgh!");
            } catch (Exception $e) {
                    Log::info( $e);
            }
        }     
    }
}

?>