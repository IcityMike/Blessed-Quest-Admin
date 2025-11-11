<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    
	protected $dates = ['created_at','updated_at','deleted_at'];
	public $timestamps = false;

	/*** Table associated with Model ***/
    protected $table = 'external_activity_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_name','description','subject_id','subject_type','causer_id', 'causer_type', 'properties'
    ];
    


    /***
    *   Developed by: Radhika Savaliya
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
                if(isset($properties['type'])) unset($properties['type']);
                if(isset($properties['user_id'])) unset($properties['user_id']);
                if(isset($properties['created_at'])) unset($properties['created_at']);
                if(isset($properties['updated_at'])) unset($properties['updated_at']);
                if(isset($properties['deleted_at'])) unset($properties['deleted_at']);
                if(isset($properties['profile_picture'])) unset($properties['profile_picture']);
                if(isset($properties['activation_code'])) unset($properties['activation_code']);
                if(isset($properties['email_verified_at'])) unset($properties['email_verified_at']);
                if(isset($properties['portfolio_details'])) unset($properties['portfolio_details']);
                if(isset($properties['remember_token'])) unset($properties['remember_token']);
                if(isset($properties['created_by'])) unset($properties['created_by']);
                if(isset($properties['status'])) unset($properties['status']);
                if(isset($properties['summary'])) unset($properties['summary']);
                if(isset($properties['callback_date'])) $properties['callback_date'] = date("d/m/Y", strtotime($properties['callback_date']));
                if(isset($properties['content'])) unset($properties['content']);
                if(isset($properties['is_featured'])) unset($properties['is_featured']);
                if(isset($properties['body'])) unset($properties['body']);
                if(isset($properties['subject'])) unset($properties['subject']);
                if(isset($properties['log_name'])) unset($properties['log_name']);
                if(isset($properties['subject_type'])) unset($properties['subject_type']);
                if(isset($properties['time'])) $properties['time'] = date("d/m/Y H:i:s", strtotime($properties['time']));

                $history['properties'] = array_filter($properties);

                $history['description'] = $d->description;
                $logHistory[] = $history;
            }
        }
        return $logHistory;
        
    }

 
     /***
    *   Developed by: Radhika Savaliya
    *   Description: Get email log history 
    ***/
    public static function getUserName($userType, $userId)
    {
        $name = "Unknown";
        if($userType == 'User')
        {
            $user = User::find($userId);
            
        }
        else if($userType == 'Admin')
        {
            $user = Admin::find($userId);
          
        }
        else if($userType == 'ReferralPartner')
        {
            $user = ReferralPartner::find($userId);
            
        }

        if(isset($user))
        {
            $name = $user->first_name." ".$user->last_name;
        }
       
        return $name;
    }

   
}

?>