<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination_details extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','name','blessed_location_details_id','location'
    ];

    public function destination_images()
    {
        return $this->hasMany('App\Models\Destination_details_img','destination_details_id')->select(['destination_details_id', 'image']);
    }

}

?>