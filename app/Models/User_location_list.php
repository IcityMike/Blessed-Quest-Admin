<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blessed_location_list extends Model
{
	use SoftDeletes;
	protected $table = 'blessed_location_list';

    
    protected $fillable = [
      'user_id','user_location_details_id','description','img','latitude','longitude','mile'
    ];

}
