<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blessed_location_details extends Model
{
	use SoftDeletes;
	protected $table = 'blessed_location_details';

    
    protected $fillable = [
      'user_id','description','img','latitude','longitude','name'
    ];

}
