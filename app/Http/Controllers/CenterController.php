<?php
namespace App\Http\Controllers;

use App\Models\VehicleCenter;
use Illuminate\Http\Request;

class CenterController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'lock_area' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'id' => $request->id,
        ]);

        // Create a new center
        VehicleCenter::create([
            'name' => $request->name,
            'vehicle_type' => $request->vehicle_type,
            'lock_area' => $request->lock_area,
            'price' => $request->price,
            'id' => $request->id,
        ]);

        // Redirect or return success message
        return redirect()->back()->with('success', 'Center added successfully!');
    }
}
