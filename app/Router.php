<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Router extends Model
{
	use SoftDeletes;
    //
    protected $fillable = [
        'sapid',
        'hostname',
        'loopback',
        'macaddress',  
    ];
    
}
