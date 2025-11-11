<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    
	protected $dates = ['created_at','updated_at','deleted_at'];
	public $timestamps = false;

	/*** Table associated with Model ***/
    protected $table = 'activity_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_name','description','subject_id','subject_type','causer_id', 'causer_type', 'properties'
    ];
    


    /***
    *   Developed by: Khushbu Jajal
    *   Description: Get activity log history 
    ***/
    public static function getHistory($startDate = null, $endDate = null)
    {
        
        $query = self::query();
        if($startDate){
            $startDate = str_replace('/', '-', $startDate);
            $startDate = date("Y-m-d H:i:s", strtotime($startDate));
            
            $query->where('created_at','>=',$startDate);
        }
        if($endDate){
            $endDate = str_replace('/', '-', $endDate);
            $endDate = date("Y-m-d H:i:s", strtotime($endDate." 23:59:59"));
            $query->where('created_at','<=',$endDate);
        }
       
        $data = $query->orderBy('created_at','desc')->get();
        $logHistory = [];
        if($data)
        {
            foreach($data as $d)
            {
                $history['id'] = $d->id;
                $causerTypeArr = explode("\\", $d->causer_type);
                $userType = $causerTypeArr[count($causerTypeArr)-1];
                $history['user_type'] = $userType;
                $history['user_name'] = self::getUserName($userType, $d->causer_id);
                $history['created_at'] = date("d/m/Y H:i:s",strtotime($d->created_at));
                
                $properties = (is_object(json_decode($d->properties)) ? get_object_vars(json_decode($d->properties)) : []);

                if(isset($properties['id'])) unset($properties['id']);
                if(isset($properties['plainPassword '])) unset($properties['plainPassword']);
                if(isset($properties['admin_id'])) unset($properties['admin_id']);
                if(isset($properties['advisor_id'])) unset($properties['advisor_id']);
                if(isset($properties['referred_by'])) unset($properties['referred_by']);
                if(isset($properties['updated_by'])) unset($properties['updated_by']);
                if(isset($properties['approved_by'])) unset($properties['approved_by']);
                if(isset($properties['created_at'])) unset($properties['created_at']);
                if(isset($properties['updated_at'])) unset($properties['updated_at']);
                if(isset($properties['deleted_at'])) unset($properties['deleted_at']);
                if(isset($properties['profile_picture'])) unset($properties['profile_picture']);
                if(isset($properties['activation_code'])) unset($properties['activation_code']);
                if(isset($properties['email_verified_at'])) unset($properties['email_verified_at']);
                if(isset($properties['is_trial_active'])) unset($properties['is_trial_active']);
                if(isset($properties['remember_token'])) unset($properties['remember_token']);
                if(isset($properties['created_by'])) unset($properties['created_by']);
                if(isset($properties['category_id'])) unset($properties['category_id']);
                if(isset($properties['description'])) unset($properties['description']);
                if(isset($properties['role'])) unset($properties['role']);
                if(isset($properties['tax_invoice_records'])) unset($properties['tax_invoice_records']);
                if(isset($properties['support_ticket'])) unset($properties['support_ticket']);
                if(isset($properties['car'])) unset($properties['car']);
                if(isset($properties['time'])) $properties['time'] = date("d/m/Y H:i:s", strtotime($properties['time']));
                if(isset($properties['body'])) unset($properties['body']);
                if(isset($properties['subject'])) unset($properties['subject']);
                if(isset($properties['log_name'])) unset($properties['log_name']);
                if(isset($properties['subject_type'])) unset($properties['subject_type']);
                if(isset($properties['role_id'])) unset($properties['role_id']);
                if(isset($properties['company_id'])) unset($properties['company_id']);
                if(isset($properties['car_id'])) unset($properties['car_id']);
                if(isset($properties['product_id'])) unset($properties['product_id']);
                if(isset($properties['advisor_id'])) unset($properties['advisor_id']);
                if(isset($properties['provider_id'])) unset($properties['provider_id']);
                if(isset($properties['advisor'])) unset($properties['advisor']);
                if(isset($properties['replied_by'])) unset($properties['replied_by']);
                if(isset($properties['ticket_id'])) unset($properties['ticket_id']);
                if(isset($properties['replied_role'])) unset($properties['replied_role']);
                if(isset($properties['category_id'])) unset($properties['category_id']);
                if(isset($properties['registration_date'])) $properties['registration_date'] = date("d/m/Y",strtotime($properties['registration_date']));
                if(isset($properties['date'])) $properties['date'] = date("d/m/Y",strtotime($properties['date']));
                if(isset($properties['invoice_date'])) $properties['invoice_date'] = date("d/m/Y",strtotime($properties['invoice_date']));

                $history['properties'] = array_filter($properties);
                $history['description'] = $d->description;
                $logHistory[] = $history;
            }
        }
        //print_r($logHistory); die;
        return $logHistory;
        
    }

      /***
    *   Developed by: Khushbu Jajal
    *   Description: Get activity log history for advisor
    ***/
    public static function getHistoryForAdvisor($advisorId=null, $startDate = null, $endDate = null)
    {
        $employees = Employee::where('advisor_id',$advisorId)->pluck('id')->toArray();
        $query = self::where( function ($q) use( $advisorId ,$employees) {  
                    $q->where(function ($qu) use( $advisorId ) {
                        $qu->where('causer_type','App\Models\Advisor')->where('causer_id', $advisorId);
                    })->orWhere( function ($qu) use( $employees ) {            
                        $qu->where('causer_type','App\Models\Employee')->whereIn('causer_id', $employees);
                    });  
                
            });
        if($startDate){
            $startDate = str_replace('/', '-', $startDate);
            $startDate = date("Y-m-d H:i:s", strtotime($startDate));
            
            $query->where('created_at','>=',$startDate);
        }
        if($endDate){
            $endDate = str_replace('/', '-', $endDate);
            $endDate = date("Y-m-d H:i:s", strtotime($endDate." 23:59:59"));
            $query->where('created_at','<=',$endDate);
        }
       
        $data = $query->orderBy('created_at','desc')->get();
        $logHistory = [];
        if($data)
        {
            foreach($data as $d)
            {
                $history['id'] = $d->id;
                $causerTypeArr = explode("\\", $d->causer_type);
                $userType = $causerTypeArr[count($causerTypeArr)-1];
                $history['user_type'] = $userType;
                $history['user_name'] = self::getUserName($userType, $d->causer_id);
                $history['created_at'] = date("d/m/Y H:i:s",strtotime($d->created_at));
                
                $properties = (is_object(json_decode($d->properties)) ? get_object_vars(json_decode($d->properties)) : []);

                if(isset($properties['id'])) unset($properties['id']);
                if(isset($properties['plainPassword '])) unset($properties['plainPassword']);
                if(isset($properties['admin_id'])) unset($properties['admin_id']);
                if(isset($properties['advisor_id'])) unset($properties['advisor_id']);
                if(isset($properties['referred_by'])) unset($properties['referred_by']);
                if(isset($properties['updated_by'])) unset($properties['updated_by']);
                if(isset($properties['approved_by'])) unset($properties['approved_by']);
                if(isset($properties['created_at'])) unset($properties['created_at']);
                if(isset($properties['updated_at'])) unset($properties['updated_at']);
                if(isset($properties['deleted_at'])) unset($properties['deleted_at']);
                if(isset($properties['profile_picture'])) unset($properties['profile_picture']);
                if(isset($properties['activation_code'])) unset($properties['activation_code']);
                if(isset($properties['email_verified_at'])) unset($properties['email_verified_at']);
                if(isset($properties['is_trial_active'])) unset($properties['is_trial_active']);
                if(isset($properties['remember_token'])) unset($properties['remember_token']);
                if(isset($properties['created_by'])) unset($properties['created_by']);
                if(isset($properties['category_id'])) unset($properties['category_id']);
                if(isset($properties['description'])) unset($properties['description']);
                if(isset($properties['role'])) unset($properties['role']);
                if(isset($properties['tax_invoice_records'])) unset($properties['tax_invoice_records']);
                if(isset($properties['support_ticket'])) unset($properties['support_ticket']);
                if(isset($properties['car'])) unset($properties['car']);
                if(isset($properties['time'])) $properties['time'] = date("d/m/Y H:i:s", strtotime($properties['time']));
                if(isset($properties['body'])) unset($properties['body']);
                if(isset($properties['subject'])) unset($properties['subject']);
                if(isset($properties['log_name'])) unset($properties['log_name']);
                if(isset($properties['subject_type'])) unset($properties['subject_type']);
                if(isset($properties['role_id'])) unset($properties['role_id']);
                if(isset($properties['company_id'])) unset($properties['company_id']);
                if(isset($properties['car_id'])) unset($properties['car_id']);
                if(isset($properties['product_id'])) unset($properties['product_id']);
                if(isset($properties['advisor_id'])) unset($properties['advisor_id']);
                if(isset($properties['provider_id'])) unset($properties['provider_id']);
                if(isset($properties['advisor'])) unset($properties['advisor']);
                if(isset($properties['replied_by'])) unset($properties['replied_by']);
                if(isset($properties['ticket_id'])) unset($properties['ticket_id']);
                if(isset($properties['replied_role'])) unset($properties['replied_role']);
                if(isset($properties['category_id'])) unset($properties['category_id']);
                if(isset($properties['registration_date'])) $properties['registration_date'] = date("d/m/Y",strtotime($properties['registration_date']));
                if(isset($properties['date'])) $properties['date'] = date("d/m/Y",strtotime($properties['date']));
                if(isset($properties['invoice_date'])) $properties['invoice_date'] = date("d/m/Y",strtotime($properties['invoice_date']));

                $history['properties'] = array_filter($properties);
                $history['description'] = $d->description;
                $logHistory[] = $history;
            }
        }
        //print_r($logHistory); die;
        return $logHistory;
        
    }

     /***
    *   Developed by: Khushbu Jajal
    *   Description: Get email log history 
    ***/
    public static function getUserName($userType, $userId)
    {
        $name = "Unknown";
        if($userType == 'Client')
        {
            $user = Client::find($userId);
            
        }
        else if($userType == 'Admin')
        {
            $user = Admin::find($userId);
          
        }
        else if($userType == 'Advisor')
        {
            
            $user = Advisor::find($userId);
           
        }
        else if($userType == 'ReferralPartner')
        {
            $user = ReferralPartner::find($userId);
            
        }
        else if($userType == 'Employee')
        {
            $user = Employee::find($userId);
            
        }

        if(isset($user))
        {
            $name = $user->first_name." ".$user->last_name;
        }
       
        return $name;
    }

   
}

?>