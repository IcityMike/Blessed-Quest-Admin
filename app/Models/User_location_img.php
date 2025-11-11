<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_location_img extends Model
{
	use SoftDeletes;
	protected $table = 'user_location_img';

    
    protected $fillable = [
      'user_id','img','user_location_id'
    ];

}
