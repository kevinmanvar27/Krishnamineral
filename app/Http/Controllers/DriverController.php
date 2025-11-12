<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query();

        $query->where('table_type', 'sales');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->driver) {
            $query->where('driver', 'like', '%' . $request->driver . '%');
        }

        if ($request->contact_number) {
            $query->where('contact_number', 'like', '%' . $request->contact_number . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $drivers = $query->latest()->paginate(5);

        $allNames = Driver::select('name')->distinct()->pluck('name');
        $allDates = Driver::select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));
        $allDrivers = Driver::select('driver')->distinct()->pluck('driver');
        $allContacts = Driver::select('contact_number')->distinct()->pluck('contact_number');
        
        if ($request->ajax()) {
            return view('driver.index', compact('drivers', 'allNames', 'allDrivers', 'allContacts', 'allDates'))
                ->with('i', ($drivers->currentPage() - 1) * $drivers->perPage());
        }

        return view('driver.index', compact('drivers', 'allNames', 'allDrivers', 'allContacts', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('driver.add-edit');
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers')->where(function ($query) {
                    $query->where('table_type', 'sales');
                }),
            ],
            'driver' => 'required',
            'contact_number' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
        ],[
            'name.required' => 'Driver Name is required',
            'driver.required' => 'Driver Type is required',
            'contact_number.required' => 'Contact Number is required'
        ]);
        $validated['table_type'] = 'sales';
        
        $drivers = Driver::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $drivers->id,
                'name' => $drivers->name,
                // 'driver' => $drivers->driver,
                // 'contact_number' => $drivers->contact_number,
            ]);
        }

        return redirect()->route(Auth::user()->can('edit-driver') ? 'driver.editIndex' : 'home')
            ->with('success', 'Driver created successfully.');
    }


    public function editIndex(Request $request)
    {
        $query = Driver::query();

        $query->where('table_type', 'sales');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->driver) {
            $query->where('driver', 'like', '%' . $request->driver . '%');
        }

        if ($request->contact_number) {
            $query->where('contact_number', 'like', '%' . $request->contact_number . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $drivers = $query->latest()->paginate(5);

        $allNames = Driver::select('name')->distinct()->pluck('name');
        $allDates = Driver::select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));
        $allDrivers = Driver::select('driver')->distinct()->pluck('driver');
        $allContacts = Driver::select('contact_number')->distinct()->pluck('contact_number');
        
        if ($request->ajax()) {
            return view('driver.edit-index', compact('drivers', 'allNames', 'allDrivers', 'allContacts', 'allDates'))
                ->with('i', ($drivers->currentPage() - 1) * $drivers->perPage());
        }
    
        return view('driver.edit-index', compact('drivers', 'allNames', 'allDrivers', 'allContacts', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function edit($id)
    {
        $drivers = Driver::find($id);
        return view('driver.add-edit', compact('drivers'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'driver' => 'required',
            'contact_number' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
        ],[
            'name.required' => 'Driver Name is required',
            'driver.required' => 'Driver Type is required',
        ]);
        $validated['table_type'] = 'sales';
        Driver::find($id)->update($validated);
        return redirect()->route(Auth::user()->can('edit-driver') ? 'driver.editIndex' : 'home')
            ->with('success', 'Driver updated successfully');
    }

    public function show($id)
    {
        $drivers = Driver::find($id);
        return view('driver.show', compact('drivers'));
    }
}
