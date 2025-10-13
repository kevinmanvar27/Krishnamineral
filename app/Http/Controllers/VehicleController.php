<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Vehicle::query();

        $query->where('table_type', 'sales');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->vehicle_name) {
            $query->where('vehicle_name', 'like', '%' . $request->vehicle_name . '%');
        }

        if ($request->contact_number) {
            $query->where('contact_number', 'like', '%' . $request->contact_number . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $vehicles = $query->latest()->paginate(5);

        $allNames = Vehicle::select('name')->distinct()->pluck('name');
        $allDates = Vehicle::select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));
        $allVehicles = Vehicle::select('vehicle_name')->distinct()->pluck('vehicle_name');
        $allContacts = Vehicle::select('contact_number')->distinct()->pluck('contact_number');
        
        if ($request->ajax()) {
            return view('vehicle.index', compact('vehicles', 'allNames', 'allVehicles', 'allContacts', 'allDates'))
                ->with('i', ($vehicles->currentPage() - 1) * $vehicles->perPage());
        }

        return view('vehicle.index', compact('vehicles', 'allNames', 'allVehicles', 'allContacts', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function editIndex(Request $request)
    {
        $query = Vehicle::query();

        $query->where('table_type', 'sales');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->vehicle_name) {
            $query->where('vehicle_name', 'like', '%' . $request->vehicle_name . '%');
        }

        if ($request->contact_number) {
            $query->where('contact_number', 'like', '%' . $request->contact_number . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $vehicles = $query->latest()->paginate(5);

        $allNames = Vehicle::select('name')->distinct()->pluck('name');
        $allDates = Vehicle::select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));
        $allVehicles = Vehicle::select('vehicle_name')->distinct()->pluck('vehicle_name');
        $allContacts = Vehicle::select('contact_number')->distinct()->pluck('contact_number');
        
        if ($request->ajax()) {
            return view('vehicle.edit-index', compact('vehicles', 'allNames', 'allVehicles', 'allContacts', 'allDates'))
                ->with('i', ($vehicles->currentPage() - 1) * $vehicles->perPage());
        }

        return view('vehicle.edit-index' , compact('vehicles', 'allNames', 'allVehicles', 'allContacts', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function show($id)
    {
        $vehicle = Vehicle::find($id);
        return view('vehicle.show', compact('vehicle'));
    }

    public function create()
    {
        return view('vehicle.create-vehicle');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:15',
                'regex:/^[A-Z]{2}[ -]?[0-9]{2}[ -]?[A-Z]{1,2}[ -]?[0-9]{4}$/',
                Rule::unique('vehicles')->where(function ($query) {
                    $query->where('table_type', 'sales');
                }),
            ],
            'vehicle_name' => 'required|string|max:255',
            'vehicle_tare_weight' => 'nullable|numeric',
            'contact_number' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
        ]);
        $validated['table_type'] = 'sales';
        $vehicles = Vehicle::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $vehicles->id,
                'name' => $vehicles->name,
                'vehicle_name' => $vehicles->vehicle_name,
                'vehicle_tare_weight' => $vehicles->vehicle_tare_weight,
                'contact_number' => $vehicles->contact_number,
            ]);
        }

        return redirect()->route('vehicle.index')->with('success', 'Vehicle Added Successfully');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        
        return redirect()->route('vehicle.index')
            ->with('success', 'Vehicle deleted successfully');
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicle.create-vehicle', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:15',
                'regex:/^[A-Z]{2}[ -]?[0-9]{2}[ -]?[A-Z]{1,2}[ -]?[0-9]{4}$/',
                Rule::unique('vehicles')->ignore($vehicle)->where(function ($query) {
                    $query->where('table_type', 'sales');
                }),
            ],
            'vehicle_name' => 'required|string|max:255',
            'vehicle_tare_weight' => 'nullable|numeric',
            'contact_number' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
        ]);
        $validated['table_type'] = 'sales';
        $vehicle->update($validated);
    
        return redirect()->route('vehicles.editIndex')->with('success', 'Vehicle Updated Successfully');
    }

    public function fetchDetails(Request $request)
    {
        // ... (This method remains the same) ...
        $vehicleId = $request->input('id');
        if (!$vehicleId) {
            return response()->json(['error' => 'Vehicle ID not provided.'], 400);
        }

        $vehicle = DB::table('vehicles')->where('id', $vehicleId)->first();

        if ($vehicle) {
            return response()->json($vehicle);
        } else {
            return response()->json(['error' => 'Vehicle not found.'], 404);
        }
    }
}
