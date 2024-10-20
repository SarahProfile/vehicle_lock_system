<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCenterPrice extends Model 
{

    protected $table = 'lock_centers_prices';
    protected $fillable = ['price1','price2','price3','price4','center_id'];
    public $timestamps = false;
    public function vehicleCenter()
    {
        return $this->hasMany(VehicleCenter::class, 'center_id');
    }
    

}