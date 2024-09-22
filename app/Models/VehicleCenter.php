<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCenter extends Model 
{

    protected $table = 'lock_centers';
    protected $fillable = ['vehicle_id','center_id'];
    public $timestamps = true;
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    

}