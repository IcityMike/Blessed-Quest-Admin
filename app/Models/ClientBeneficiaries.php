<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientBeneficiaries extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','name','alias','address','city','country_code','email','bank_account_type','account_type','contact_number','state','postcode','account_number','bank_name','bank_code','identification_type','identification_value','routing_code_type_1','routing_code_value_1','country','routing_code_type_2','routing_code_value_2','purpose_code'
    ];

  
}