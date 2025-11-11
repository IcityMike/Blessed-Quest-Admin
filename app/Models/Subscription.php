<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

	protected $table = 'subscriptions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id','subscription_type','user_id','start_date','end_date','status','product_id','title','sub_title','amount','description','per_year_amount','services','detail_description','try_bottom_button_text'
    ];

}

?>