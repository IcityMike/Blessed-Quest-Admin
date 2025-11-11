<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Events_user extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'events_user';

    protected $fillable = [
        'event_name','library_id','status','admin_id','user_id','user_song','event_id','description'
    ];

  
}