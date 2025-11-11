<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{

    protected $table = "transaction_histories";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id','reference_number','status','event','status_timestamp','created_at','updated_at'
    ];

    public function getStatusTimestampAttribute()
    {
        return ($this->attributes['status_timestamp']) ? date("d M,Y",strtotime($this->attributes['status_timestamp'])) : '';
    }

    public function getStatusAttribute()
    {
        
        return ($this->attributes['status']) ? config('settings.nium_transaction_status')[$this->attributes['status']] : '-';
    }
}

?>