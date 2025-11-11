<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Types_of_voice extends Model
{
	use SoftDeletes;
	protected $table = 'types_of_voice';

	public $timestamps = true;
    
    protected $fillable = [
      'type_of_voice','status'
    ];
}