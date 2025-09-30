<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $drivers = Driver::latest()->paginate(5);
        return view('driver.index', compact('drivers'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('driver.add-edit');
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'name' => 'required',
            'driver' => 'required',
            'contact_number' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
        ],[
            'name.required' => 'Driver Name is required',
            'driver.required' => 'Driver Type is required',
            'contact_number.required' => 'Contact Number is required'
        ]);
        $drivers = Driver::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $drivers->id,
                'name' => $drivers->name,
                // 'driver' => $drivers->driver,
                // 'contact_number' => $drivers->contact_number,
            ]);
        }

        return redirect()->route('driver.index')
            ->with('success', 'Driver created successfully.');
    }


    public function editIndex(Request $request)
    {
        $drivers = Driver::latest()->paginate(5);
        return view('driver.edit-index', compact('drivers'))
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
        Driver::find($id)->update($validated);
        return redirect()->route('driver.editIndex')
            ->with('success', 'Driver updated successfully');
    }

    public function show($id)
    {
        $drivers = Driver::find($id);
        return view('driver.show', compact('drivers'));
    }
}
