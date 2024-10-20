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
            'price1' => 'required',
            'price2' => 'required',
            'price3' => 'required',
            'price4' => 'required',
            'center_id' => 'required',
          
        ]);

        // Create a new center
        VehicleCenterPrice::create([
            'price1' => $request->price1,
            'price2' => $request->price2,
            'price3' => $request->price3,
            'price4' => $request->price4,
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
