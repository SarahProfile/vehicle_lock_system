<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    // Specify the fields that are mass assignable
    protected $fillable = [
        // 'vehicle_center', 
        'enter_date', 
        'exit_date',  // Add exit_date
        'lock_location', 
        'lock_area', 
        'vehicle_number', 
        'vehicle_model', 
        'chassis_number', 
        'vehicle_type',
        'vehicle_price' ,// Add vehicle_price
        'vehicle_status',
        'vehicle_center_id'
    ];

    public function calculatePrice($exitDate)
{
    $enterDate = new \DateTime($this->enter_date);
    $exitDate = new \DateTime($exitDate);

    // Calculate the difference in hours
    $interval = $enterDate->diff($exitDate);
    $hours = ($interval->days * 24) + $interval->h;

    // Determine the price per hour
    if ($this->vehicle_type == 'صغيرة' && $this->lock_area == 'داخل المنطقة') {
        $pricePerHour = 500;
    } elseif ($this->vehicle_type == 'صغيرة' && $this->lock_area == 'خارج المنطقة') {
        $pricePerHour = 800;
    } elseif ($this->vehicle_type == 'كبيرة' && $this->lock_area == 'خارج المنطقة') {
        $pricePerHour = 1500;
        } 
     elseif ($this->vehicle_type == 'كبيرة' && $this->lock_area == 'داخل المنطقة') {
    $pricePerHour = 1000;
    } 
    elseif ($this->vehicle_type == 'المعدات' && $this->lock_area == 'داخل المنطقة') {
        $pricePerHour = 2000;
        } 
        elseif ($this->vehicle_type == 'المعدات' && $this->lock_area == 'خارج المنطقة') {
            $pricePerHour = 2700;
            } 
    else {
        $pricePerHour = 0; // Default case, adjust as necessary
    }

    // Calculate total price
    return ($pricePerHour * $hours) + (2 * $hours);
}

    /**
     * Define the relationship with VehicleImage model.
     * A vehicle can have many images.
     */
    public function images()
    {
        return $this->hasMany(VehicleImage::class);
    }
// In the Vehicle model (Vehicle.php)
public function vehicleCenter()
{
    return $this->belongsTo(VehicleCenter::class, 'vehicle_center_id');
}

}

