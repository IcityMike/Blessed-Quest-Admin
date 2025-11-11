<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source_details extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','name','blessed_location_details_id','location'
    ];

    public function source_images()
    {
        return $this->hasMany('App\Models\Source_details_img','source_details_id')->select(['source_details_id', 'image']);
    }

}

?>