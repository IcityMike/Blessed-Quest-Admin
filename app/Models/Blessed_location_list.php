<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blessed_location_list extends Model
{
	use SoftDeletes;
	protected $table = 'blessed_location_list';

    
    protected $fillable = [
      'user_id','blessed_location_details_id','description','img','latitude','longitude','name','mile'
    ];


    public function location_images()
    {
       // return $this->hasMany('App\Models\Blessed_location_imgs','blessed_location_id')->select(['blessed_location_id', 'image_name']);

    	return $this->hasMany('App\Models\Blessed_location_imgs', 'blessed_location_list_id', 'id');
    }
}
