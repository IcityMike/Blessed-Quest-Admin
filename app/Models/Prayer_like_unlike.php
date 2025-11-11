<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prayer_like_unlike extends Model
{
	use SoftDeletes;
	protected $table = 'prayer_like_unlike';

    
    protected $fillable = [
      'user_id','prayer_id','like_unlike'
    ];

}
