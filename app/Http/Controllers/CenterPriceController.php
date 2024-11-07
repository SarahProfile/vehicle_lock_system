<?php
namespace App\Http\Controllers;

use App\Models\VehicleCenter;
use Illuminate\Http\Request;
use App\Models\VehicleCenterPrice;
class CenterPriceController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'price_small_inside' => 'required',
            'price_big_inside' => 'required',
            'price_small_outside' => 'required',
            'price_big_outside' => 'required',
            'price_equipment_inside' => 'required',
            'price_equipment_outside' => 'required',
            'center_id' => 'required',
          
        ]);

        // Create a new center
        VehicleCenterPrice::create([
            'price_small_inside' => $request->price_small_inside,
            'price_big_inside' => $request->price_big_inside,
            
            'price_small_outside' => $request->price_small_outside,
            'price_big_outside' => $request->price_big_outside,
            'price_equipment_inside' => $request->price_equipment_inside,
            'price_equipment_outside' => $request->price_equipment_outside,
            'center_id' => $request->center_id,
           
        ]);
      

        // Redirect or return success message
        return redirect()->back()->with('success', 'Center added successfully!');
    }

    public function create()
    {
         // Assuming you have a VehicleCenter model
    $prices = VehicleCenterPrice::all(); // Fetch all centers
    $centers = VehicleCenter::all();
    return view('add-lock-centers-prices',['centers' => $centers,['prices' => $prices]]);
       
    }
}
