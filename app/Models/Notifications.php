<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
	protected $table = 'notifications';

	public $timestamps = false;
    
    protected $fillable = [
      'user_id','notification_img','notification_title','notification_details','notification_type','notify_read_unread'
    ];
}
