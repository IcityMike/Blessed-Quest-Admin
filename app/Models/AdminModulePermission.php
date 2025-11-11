<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdminModulePermission extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id','permission_id'
    ];

}

?>