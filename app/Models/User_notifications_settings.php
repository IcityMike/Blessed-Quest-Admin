<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User_notifications_settings extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_notifications_settings';

    protected $fillable = [
        'user_id','all_push_notifications','traffic_route_updates','receive_recommended_content','reminders_credits_expire','reminders_sub_autorenews','notify_updates_changes_account','notify_me_sub_and_sub_renewal_errors','email_notifications','receive_reminders_days_before_credits_expire_email','reminders_sub_autorenews_email','notify_updates_changes_account_email','notify_me_sub_and_sub_renewal_errors_email'
    ];

}

?>