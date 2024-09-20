<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $enterDate = $request->input('enter_date');
        $lockLoction = $request->input('lock_location');
        $vehicleStatusOrder = $request->input('vehicle_status_order'); // Manage the status order
        $enterDateOrder = $request->input('enter_date_order', 'desc'); // Manage enter date sorting
        $locationOrder = $request->input('location_order'); // Manage lock location sorting
    
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
    
        // Handle the vehicle status filter logic
        if ($vehicleStatusOrder) {
            if ($vehicleStatusOrder === 'out_first') {
                $vehicles->orderByRaw("FIELD(vehicle_status, 'خرجت', 'موجودة')");
            } elseif ($vehicleStatusOrder === 'in_first') {
                $vehicles->orderByRaw("FIELD(vehicle_status, 'موجودة', 'خرجت')");
            }
        }
    
        // Handle the sorting for the enter_date field
        if ($enterDateOrder) {
            $vehicles->orderBy('enter_date', $enterDateOrder); // Sorting by enter date after vehicle status
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
    
    
    
    
}
