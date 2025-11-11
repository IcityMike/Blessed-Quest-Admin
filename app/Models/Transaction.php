<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_code','user_name','user_email','user_phone_number','transaction_id','transaction_reference_number','transaction_status','transaction_created_at','transaction_updated_at','transaction_hold_fx_expires_on','payment_description','status_description','transaction_file','transaction_fee','transaction_amount','destination_currency','user_id','subscription_id'
    ];
    protected $appends = ['transactioncreatedat_formatted'];
    public function getTransactionCreatedAtFormattedAttribute()
    {
        return ($this->transaction_created_at) ? date("d M,Y",strtotime($this->transaction_created_at)) : '';
    }
}

?>