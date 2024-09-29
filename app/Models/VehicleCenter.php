<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCenter extends Model 
{

    protected $table = 'lock_centers';
    protected $fillable = ['vehicle_id','center_id','price','lock_area','vehicle_type'];
    public $timestamps = true;
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    

}