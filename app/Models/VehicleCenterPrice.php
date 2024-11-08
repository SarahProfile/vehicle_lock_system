<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCenterPrice extends Model 
{
    protected $table = 'lock_centers_prices';
    protected $fillable = ['price_small_inside','price_big_inside','price_small_outside','price_big_outside','price_equipment_inside','price_equipment_outside','center_id'];
    public $timestamps = false;
    public function vehicleCenter()
    {
        return $this->hasMany(VehicleCenter::class, 'center_id');
    }
}