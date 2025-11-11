<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Default_profile_picture extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $table = 'default_profile_picture';
    protected $fillable = [
        'name','image_name','status'
    ];

  
}