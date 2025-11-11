<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source_details_img extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'source_details_id','image','user_id'
    ];

}

?>