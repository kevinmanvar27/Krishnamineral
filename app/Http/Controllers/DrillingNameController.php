<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DrillingName;

class DrillingNameController extends Controller
{
    public function index(Request $request)
    {
        $query = DrillingName::query();

        // Apply filters
        if ($request->d_name) {
            $query->where('d_name', 'like', '%' . $request->d_name . '%');
        }
        
        if ($request->phone_no) {
            $query->where('phone_no', 'like', '%' . $request->phone_no . '%');
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $drillingNames = $query->paginate(10);
        
        // Get all unique values for filter dropdowns
        $allNames = DrillingName::select('d_name')->distinct()->pluck('d_name');
        $allPhones = DrillingName::select('phone_no')->distinct()->pluck('phone_no');
        $allStatuses = DrillingName::select('status')->distinct()->pluck('status');

        if ($request->ajax()) {
            return view('drilling-name.index', compact('drillingNames', 'allNames', 'allPhones', 'allStatuses'))
                ->with('i', ($drillingNames->currentPage() - 1) * $drillingNames->perPage());
        }

        return view('drilling-name.index', compact('drillingNames', 'allNames', 'allPhones', 'allStatuses'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function editIndex(Request $request)
    {
        $query = DrillingName::query();

        // Apply filters
        if ($request->d_name) {
            $query->where('d_name', 'like', '%' . $request->d_name . '%');
        }
        
        if ($request->phone_no) {
            $query->where('phone_no', 'like', '%' . $request->phone_no . '%');
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $drillingNames = $query->paginate(10);
        
        // Get all unique values for filter dropdowns
        $allNames = DrillingName::select('d_name')->distinct()->pluck('d_name');
        $allPhones = DrillingName::select('phone_no')->distinct()->pluck('phone_no');
        $allStatuses = DrillingName::select('status')->distinct()->pluck('status');

        if ($request->ajax()) {
            return view('drilling-name.edit-index', compact('drillingNames', 'allNames', 'allPhones', 'allStatuses'))
                ->with('i', ($drillingNames->currentPage() - 1) * $drillingNames->perPage());
        }

        return view('drilling-name.edit-index', compact('drillingNames', 'allNames', 'allPhones', 'allStatuses'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        return view('drilling-name.add-edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'd_name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'status' => 'required|in:active,inactive'
        ]);

        $drillingName = DrillingName::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'dri_id' => $drillingName->dri_id,
                'd_name' => $drillingName->d_name,
                'message' => 'Drilling Name created successfully.'
            ]);
        }

        return redirect()->route('drilling-name.index')->with('success', 'Drilling Name created successfully.');
    }

    public function show($id)
    {
        $drillingName = DrillingName::findOrFail($id);
        return view('drilling-name.show', compact('drillingName'));
    }

    public function edit($id)
    {
        $drillingName = DrillingName::findOrFail($id);
        return view('drilling-name.add-edit', compact('drillingName'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'd_name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'status' => 'required|in:active,inactive'
        ]);

        $drillingName = DrillingName::findOrFail($id);
        $drillingName->update($request->all());

        return redirect()->route('drilling-name.editIndex')->with('success', 'Drilling Name updated successfully.');
    }
}