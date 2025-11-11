<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubscriptionType extends Model
{
	use SoftDeletes;
	protected $table = 'subscription_types';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'subscription_type','status'
    ];

}

?>