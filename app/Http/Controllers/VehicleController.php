<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

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
        $enterDateOrder = $request->input('enter_date_order'); // to manage the enter date sorting
        $locationOrder = $request->input('location_order'); // to manage lock location sorting
    
        // Fetch distinct lock locations from the vehicles table
        $lockLocations = Vehicle::select('lock_location')->distinct()->pluck('lock_location');
    
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
            'lockLocations' => $lockLocations // Pass lock locations to the view
        ]);
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
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return redirect('/admin/vehicles')->with('error', 'Vehicle not found.');
        }

        return view('details-vehicle', compact('vehicle'));
    }

    public function create()
    {
        return view('add-vehicle');
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
{
    // Validate the form data
    $validatedData = $request->validate([
        'vehicle_center' => 'required',
        'enter_date' => 'required',
        'exit_date' => 'required', // Add validation for exit_date
        'lock_location' => 'required',
        'lock_area' => 'required',
        'vehicle_number' => 'required',
        'vehicle_model' => 'required',
        'chassis_number' => 'required',
        'vehicle_type' => 'required',
        'exit_date'=>'required',
        'vehicle_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
    ]);

    // Calculate the time difference in hours between enter_date and exit_date
    $enterDate = new \DateTime($request->input('enter_date'));
    $exitDate = new \DateTime($request->input('exit_date'));
    $hours = $enterDate->diff($exitDate)->h;

    // Calculate the price based on vehicle_type, lock_area, and hours
    $pricePerHour = 0;

    if ($request->input('vehicle_type') == 'صغيرة' && $request->input('lock_area') == 'داخل المنطقة') {
        $PerHour = 500;
    } elseif ($request->input('vehicle_type') == 'صغيرة' && $request->input('lock_area') == 'خارج المنطقة') {
        $pricePerHour = 800;
    } elseif ($request->input('vehicle_type') == 'كبيرة' && $request->input('lock_area') == 'خارج المنطقة') {
        $pricePerHour = 1500;
    }
elseif ($request->input('vehicle_type') == 'كبيرة' && $request->input('lock_area') == 'داخل المنطقة') {
    $pricePerHour = 1000;
}
 elseif ($request->input('vehicle_type') == 'المعدات' && $request->input('lock_area') == 'خارج المنطقة') {
    $pricePerHour = 2700;
}
elseif ($request->input('vehicle_type') == 'المعدات' && $request->input('lock_area') == 'داخل المنطقة') {
    $pricePerHour = 2000;
}

    $vehiclePrice = $hours * $pricePerHour;

    // Add the calculated price to the validated data
    $validatedData['vehicle_price'] = $vehiclePrice;

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

    return redirect()->route('home')->with('success', 'Vehicle added successfully with a price of ' . $vehiclePrice . ' ريال.');
}
public function showExitForm($id)
{
    $vehicle = Vehicle::findOrFail($id);
    return view('exit_vehicle', compact('vehicle'));
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
}

// Function to calculate the price based on your logic
private function calculateVehiclePrice($vehicle)
{
    $enter_date = new \DateTime($vehicle->enter_date);
    $exit_date = new \DateTime($vehicle->exit_date);
    $interval = $enter_date->diff($exit_date);
    $hours = ($interval->days * 24) + $interval->h;

    // Set price based on vehicle_type and lock_area
    $price_per_hour = 0;
    if ($vehicle->vehicle_type === 'صغيرة') {
        if ($vehicle->lock_area === 'داخل المنطقة') {
            $price_per_hour = 500;
        } elseif ($vehicle->lock_area === 'خارج المنطقة') {
            $price_per_hour = 800;
        }
    } elseif ($vehicle->vehicle_type === 'كبيرة' && $vehicle->lock_area === 'خارج المنطقة') {
        $price_per_hour = 1500;
    }
 elseif ($vehicle->vehicle_type === 'كبيرة' && $vehicle->lock_area === 'داخل المنطقة') {
    $price_per_hour = 1000;
}
elseif ($vehicle->vehicle_type === 'المعدات' && $vehicle->lock_area === 'داخل المنطقة') {
    $price_per_hour = 2000;
}
elseif ($vehicle->vehicle_type === 'المعدات' && $vehicle->lock_area === 'خارج المنطقة') {
    $price_per_hour = 2700;
}
    // Total price
    return ($price_per_hour * $hours) + (2 * $hours);
}

public function exitVehicle(Request $request, $id)
{
    $vehicle = Vehicle::findOrFail($id);
    
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

    // Validate the form data
    $validatedData = $request->validate([
        'vehicle_center' => 'required',
        'enter_date' => 'required',
        'lock_location' => 'required',
        'lock_area' => 'required',
        'vehicle_number' => 'required',
        'vehicle_model' => 'required',
        'chassis_number' => 'required',
        'vehicle_type' => 'required',
        'vehicle_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validation for multiple images
    ]);

    // Update the vehicle data
    $vehicle->update($validatedData);

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

    return redirect()->route('home')->with('success', 'Vehicle updated successfully.');
}

    


    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit($id)
    {
        $vehicle = Vehicle::find($id);
        return view('edit-vehicle', ['vehicle' => $vehicle]);
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