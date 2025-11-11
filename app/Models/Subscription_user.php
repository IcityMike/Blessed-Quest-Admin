<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription_user extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id','subscription_type','user_id','start_date','end_date','status','product_id','title','sub_title','amount','description','subscription_id','cancel_date','last_active_date','services'
    ];

}

?>