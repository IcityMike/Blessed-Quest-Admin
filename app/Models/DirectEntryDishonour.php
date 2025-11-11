<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DirectEntryDishonour extends Model
{
     /*** Table associated with Model ***/
    protected $table = 'direct_entry_dishonours';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ReturnDate','Amount','AccountName','AccountNumber','Bsb','Token','Type','ReturnReason','TransactionDate','OriginalTransactionId','TransactionReference','created_at','updated_at','request_ip','request_url'
    ];


    public function getReturnDateAttribute($value): ?string {
        return !is_null($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : NULL;
    }

    public function getTransactionDateAttribute($value): ?string {
        return !is_null($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : NULL;
    }

    public function getCreatedAtAttribute($value): ?string {
        return !is_null($value) ? Carbon::parse($value)->format('d/m/Y H:i:s') : NULL;
    }

  
}
