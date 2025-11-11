<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AdminModule extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function module_permissions()
    {
        return $this->hasMany(AdminModulePermission::class,'module_id');
    }

}

?>