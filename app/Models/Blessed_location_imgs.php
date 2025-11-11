<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blessed_location_imgs extends Model
{
	use SoftDeletes;
	protected $table = 'blessed_location_imgs';

    
    protected $fillable = [
      'user_id','image_name','blessed_location_id','blessed_location_list_id'
    ];

}
