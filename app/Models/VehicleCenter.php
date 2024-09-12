<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCenter extends Model 
{

    protected $table = 'vehicle_centers';
    protected $fillable = ['vehicle_id','center_id'];
    public $timestamps = true;
    public function centers()
    {
        return $this->hasMany(LockCenter::class);
    }

}