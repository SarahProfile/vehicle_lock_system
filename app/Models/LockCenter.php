<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LockCenter extends Model 
{

    protected $table = 'lock_centers';
    protected $fillable = ['name','id'];
    public $timestamps = true;

}