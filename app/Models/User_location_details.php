<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_location_details extends Model
{
	use SoftDeletes;
	protected $table = 'user_location_details';

    
    protected $fillable = [
      'name','user_id','description','img','latitude','longitude','mile'
    ];

}
