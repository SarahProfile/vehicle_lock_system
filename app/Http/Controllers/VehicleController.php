<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleCenter;
use App\Models\VehicleCenterPrice;
use App\Models\VehicleImage;

class VehicleController extends Controller 
{
    /**
     * Display a listing of the vehicles.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $enterDate = $request->input('enter_date');
        $lockLoction = $request->input('lock_location');
        $vehicleStatusOrder = $request->input('vehicle_status_order'); // to manage the status order
    $enterDateOrder = $request->input('enter_date_order', 'desc'); // Default to 'desc' for new to old
        $locationOrder = $request->input('location_order'); // to manage lock location sorting
        
        // Fetch distinct lock locations from the vehicles table
        $lockLocations = Vehicle::select('lock_location')->distinct()->pluck('lock_location');
        $center = VehicleCenter::where('id', auth()->user()->lock_center_id)->first();


        // Start querying the Vehicle model
        $vehicles = Vehicle::query()
            ->when($search, function ($query, $search) {
                $query->where('vehicle_number', 'like', "%{$search}%")
                      ->orWhere('chassis_number', 'like', "%{$search}%");
            })
            ->when($enterDate, function ($query, $enterDate) {
                $query->whereDate('enter_date', $enterDate);
            })
            ->when($lockLoction, function ($query, $lockLoction) {
                $query->where('lock_location', 'like', "%{$lockLoction}%");
            });
    
        // Handle the sorting for the enter_date field
        
        if ($enterDateOrder) {
            if ($enterDateOrder === 'desc') {
                $vehicles->orderBy('enter_date', 'desc');
            } elseif ($enterDateOrder === 'asc') {
                $vehicles->orderBy('enter_date', 'asc');
            }
        }
    
        // Handle the vehicle status filter logic
        if ($vehicleStatusOrder) {
            if ($vehicleStatusOrder === 'out_first') {
                $vehicles->orderByRaw("FIELD(vehicle_status, 'خرجت', 'موجودة')");
            } elseif ($vehicleStatusOrder === 'in_first') {
                $vehicles->orderByRaw("FIELD(vehicle_status, 'موجودة', 'خرجت')");
            }
        }
    
        // Handle the lock location sorting
        if ($locationOrder) {
            $vehicles->orderBy('lock_location', $locationOrder);
        }
    
        $vehicles = $vehicles->get();
    
        return view('home', [
            'vehicles' => $vehicles,
            'enterDateOrder' => $enterDateOrder,
            'vehicleStatusOrder' => $vehicleStatusOrder,
            'locationOrder' => $locationOrder,
            'lockLocations' => $lockLocations, // Pass lock locations to the view
            'center'=> $center,

        ]);
    }
    

    public function checkUniqueness(Request $request)
{
    $field = $request->input('field');
    $value = $request->input('value');

    $exists = Vehicle::where($field, $value)->exists();

    return response()->json(['isUnique' => !$exists]);
}


    /**
     * Show the form for creating a new vehicle.
     */
    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('home', compact('vehicle'));
    }

    public function showFull($id)
    {
        // Fetch the vehicle along with its related vehicle center
        $vehicle = Vehicle::with('vehicleCenter')->find($id);
    
        if (!$vehicle) {
            return redirect('/admin/vehicles')->with('error', 'Vehicle not found.');
        }
    
        return view('details-vehicle', compact('vehicle'));
    }
    

    public function create()
    {
         // Assuming you have a VehicleCenter model
    $vehicleCenters = VehicleCenter::all(); // Fetch all centers

    return view('add-vehicle', compact('vehicleCenters'));
       
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
{
    // Validate the form data
    $validatedData = $request->validate([
        
        'vehicle_center_id' => 'required|exists:lock_centers,id', // Ensure vehicle center is valid

        'enter_date' => 'required',
        // 'exit_date' => 'required', // Add validation for exit_date
        'lock_location' => 'required',
        'lock_area' => 'required',
        'vehicle_number' => 'required',
        'vehicle_model' => 'required',
        'chassis_number' => 'required',
        'vehicle_type' => 'required',
        'vehicle_color' => 'required',
        // 'exit_date'=>'required',
        'vehicle_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
        'report_number' => 'required',
    ]);

   
    // Create the vehicle record
    $vehicle = Vehicle::create($validatedData);

    // Handle multiple image uploads
    if ($request->hasFile('vehicle_images')) {
        foreach ($request->file('vehicle_images') as $image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);

            // Save each image's path in the vehicle_images table
            $vehicle->images()->create([
                'image_path' => $imageName
            ]);
        }
    }

    return redirect()->route('home')->with('success', 'Vehicle added successfully  ' );
}
public function deleteImage($id)
{
    $image = VehicleImage::findOrFail($id); // Find the image by ID

    // Remove file from the public/images folder
    $imagePath = public_path('images/' . $image->image_path);
    if (file_exists($imagePath)) {
        unlink($imagePath); // Delete the file
    }

    // Delete image record from the database
    $image->delete();

    return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
}


// public function showExitForm($id)
// {
//     $vehicle = Vehicle::findOrFail($id);
//     return view('exit_vehicle', compact('vehicle'));
// }
public function showExitForm($vehicleId)
{
    // Fetch the vehicle record by ID
    $vehicle = Vehicle::findOrFail($vehicleId);

    // Get the center price from VehicleCenterPrice model based on vehicle's center ID
    $centerPrices = VehicleCenterPrice::all();

        // Pass the center prices to the view
        return view('exit_vehicle', compact('vehicle', 'centerPrices'));

}

private function getPricePerHour($vehicle, $centerPrice)
{
    // Check the vehicle type and lock area to determine the price per hour
    if ($vehicle->vehicle_type === 'صغيرة') {
        if ($vehicle->lock_area === 'داخل المنطقة') {
            return $centerPrice->small_inside_price; // Assuming the price is stored in these fields
        } elseif ($vehicle->lock_area === 'خارج المنطقة') {
            return $centerPrice->small_outside_price; // Assuming the price is stored in these fields
        }
    } elseif ($vehicle->vehicle_type === 'كبيرة') {
        if ($vehicle->lock_area === 'داخل المنطقة') {
            return $centerPrice->large_inside_price;
        } elseif ($vehicle->lock_area === 'خارج المنطقة') {
            return $centerPrice->large_outside_price;
        }
    }

    // Default price if no matching conditions are found
    return 0;
}


public function submitExitForm(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);
 
    // Validate the input
    $request->validate([
        'exit_date' => 'required|date|after_or_equal:' . $vehicle->enter_date,
    ]);

    // Update the exit date and status
    $vehicle->exit_date = $request->exit_date;
    $vehicle->vehicle_status = 'خرجت';

    // Calculate price logic here
    $price = $this->calculateVehiclePrice($vehicle);
    $vehicle->vehicle_price = $price;

    $vehicle->save();

    return redirect()->route('home')->with('success', 'تم إخراج المركبة بنجاح');
    //    return view('home', compact('vehicle'));
}

// Function to calculate the price based on your logic
private function calculateVehiclePrice($vehicle)
{
    $enter_date = new \DateTime($vehicle->enter_date);
    $exit_date = new \DateTime($vehicle->exit_date);
    $interval = $enter_date->diff($exit_date);
    $centerPrice=VehicleCenterPrice::where('center_id',$vehicle->vehicle_center_id)->first();

    // Calculate total hours
    $hours = ($interval->days * 24) + $interval->h;
    
    // If there are minutes or hours is 0, round up to 1 hour
    if ($hours === 0 || $interval->i > 0) {
        $hours += 1;
    }

    // Set price based on vehicle_type and lock_area
    $price_per_hour = 0;
    if ($vehicle->vehicle_type === 'صغيرة') {
        if ($vehicle->lock_area === 'داخل المنطقة') {
            $price_per_hour = $centerPrice->price_small_inside;
        } elseif ($vehicle->lock_area === 'خارج المنطقة') {
            $price_per_hour = $centerPrice->price_small_outside;
        }
    } elseif ($vehicle->vehicle_type === 'كبيرة') {
        if ($vehicle->lock_area === 'داخل المنطقة') {
            $price_per_hour = $centerPrice->price_big_inside;
        } elseif ($vehicle->lock_area === 'خارج المنطقة') {
            $price_per_hour = $centerPrice->price_big_outside;
        }
    } elseif ($vehicle->vehicle_type === 'المعدات') {
        if ($vehicle->lock_area === 'داخل المنطقة') {
            $price_per_hour = $centerPrice->price_equipment_inside;
        } elseif ($vehicle->lock_area === 'خارج المنطقة') {
            $price_per_hour =  $centerPrice->price_equipment_outside;
        }
    }

    $total_price_befor_vat = ($price_per_hour) + (2 * $hours);
    $total_price_after_vat = ($total_price_befor_vat) + (0.15 * $total_price_befor_vat);
    // Total price (including an additional 2 per hour fee)
    return $total_price_after_vat;
}

public function exitVehicle(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);
    $vehicleCenterPrices = VehicleCenterPrice::all(); // Fetch all centers
    // Validate the exit date
    $validatedData = $request->validate([
        'exit_date' => 'required|date',
    ]);
    
    // Calculate the price
    $price = $vehicle->calculatePrice($request->input('exit_date'));
    
    // Update the vehicle record
    $vehicle->update([
        'exit_date' => $request->input('exit_date'),
        'vehicle_price' => $price,
        'vehicle_status' => 'خرجت',
    ]);
    
    return redirect()->route('home')->with('success', 'تم تحديث معلومات المركبة بنجاح.');
}

public function calculatePrice(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);
    
    // Calculate the price using the model method
    $price = $vehicle->calculatePrice($request->input('exit_date'));
    
    return response()->json(['price' => $price]);
}

public function update(Request $request, $id)
{
    $vehicle = Vehicle::find($id);
    $vehicleCenters = VehicleCenter::all();

    // Validate the form data
    $validatedData = $request->validate([
        'vehicle_center_id' => 'required|exists:lock_centers,id', // Validate vehicle center
        'enter_date' => 'required',
        'lock_location' => 'required',
        'lock_area' => 'required',
        'vehicle_number' => 'required',
        'vehicle_model' => 'required',
        'chassis_number' => 'required',
        'vehicle_type' => 'required',
        'vehicle_status' => 'nullable',
        'exit_date' => 'nullable|date',
        'report_number' => 'required',
        'vehicle_color' => 'nullable',
        'vehicle_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Update the vehicle data
    $vehicle->update($validatedData);
    // $vehicleCenters->update($validatedData);
    // Calculate the price if the exit date is provided
    if (!empty($vehicle->exit_date)) {
        $price = $this->calculateVehiclePrice($vehicle);
        $vehicle->update(['vehicle_price' => $price]);
    }

    // Handle multiple image uploads
    if ($request->hasFile('vehicle_images')) {
        foreach ($request->file('vehicle_images') as $image) {
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);

            $vehicle->images()->create([
                'image_path' => $imageName
            ]);
        }
    }

    return redirect()->route('home')->with('success', 'Vehicle updated successfully.');
}



    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit($id)
    {
        $vehicle = Vehicle::find($id);
        $vehicleCenters = VehicleCenter::all(); // Fetch all vehicle centers
         // Get the center price from VehicleCenterPrice model based on vehicle's center ID
        $centerPrices = VehicleCenterPrice::all();
        return view('edit-vehicle', [
            'vehicle' => $vehicle,
            'vehicleCenters' => $vehicleCenters, // Pass the vehicle centers to the view
            'centerPrices'=>$centerPrices,// Pass the vehicle centers prices to the view
        ]);
    }
    

    /**
     * Update the specified vehicle in storage.
     */
   

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::find($id);
        $vehicle->delete();
        
        return redirect()->route('home')->with('success', 'Vehicle deleted successfully.');
    }
}
