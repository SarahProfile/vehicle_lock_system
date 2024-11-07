<?php
namespace App\Http\Controllers;

use App\Models\VehicleCenter;
use Illuminate\Http\Request;
use App\Models\VehicleCenterPrice;
class CenterController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
           
        ]);

        // Create a new center
        VehicleCenter::create([
            'name' => $request->name,
           
        ]);
      

        // Redirect or return success message
        return redirect()->route('centers.prices')->with('success', 'Center added successfully!');
    }

    public function create()
    {
         // Assuming you have a VehicleCenter model
    $vehicleCenters = VehicleCenter::all(); // Fetch all centers

    return view('add-lock-centers', compact('vehicleCenters'));
       
    }
}
