<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlasterName;

class BlasterNameController extends Controller
{
    public function index(Request $request)
    {
        $query = BlasterName::query();

        // Apply filters
        if ($request->b_name) {
            $query->where('b_name', 'like', '%' . $request->b_name . '%');
        }
        
        if ($request->phone_no) {
            $query->where('phone_no', 'like', '%' . $request->phone_no . '%');
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $blasterNames = $query->paginate(10);
        
        // Get all unique values for filter dropdowns
        $allNames = BlasterName::select('b_name')->distinct()->pluck('b_name');
        $allPhones = BlasterName::select('phone_no')->distinct()->pluck('phone_no');
        $allStatuses = BlasterName::select('status')->distinct()->pluck('status');

        if ($request->ajax()) {
            return view('blaster-name.index', compact('blasterNames', 'allNames', 'allPhones', 'allStatuses'))
                ->with('i', ($blasterNames->currentPage() - 1) * $blasterNames->perPage());
        }

        return view('blaster-name.index', compact('blasterNames', 'allNames', 'allPhones', 'allStatuses'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function editIndex(Request $request)
    {
        $query = BlasterName::query();

        // Apply filters
        if ($request->b_name) {
            $query->where('b_name', 'like', '%' . $request->b_name . '%');
        }
        
        if ($request->phone_no) {
            $query->where('phone_no', 'like', '%' . $request->phone_no . '%');
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $blasterNames = $query->paginate(10);
        
        // Get all unique values for filter dropdowns
        $allNames = BlasterName::select('b_name')->distinct()->pluck('b_name');
        $allPhones = BlasterName::select('phone_no')->distinct()->pluck('phone_no');
        $allStatuses = BlasterName::select('status')->distinct()->pluck('status');

        if ($request->ajax()) {
            return view('blaster-name.edit-index', compact('blasterNames', 'allNames', 'allPhones', 'allStatuses'))
                ->with('i', ($blasterNames->currentPage() - 1) * $blasterNames->perPage());
        }

        return view('blaster-name.edit-index', compact('blasterNames', 'allNames', 'allPhones', 'allStatuses'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        return view('blaster-name.add-edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'b_name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'status' => 'required|in:active,inactive'
        ]);

        BlasterName::create($request->all());

        return redirect()->route('blaster-name.index')->with('success', 'Blaster Name created successfully.');
    }

    public function show($id)
    {
        $blasterName = BlasterName::findOrFail($id);
        return view('blaster-name.show', compact('blasterName'));
    }

    public function edit($id)
    {
        $blasterName = BlasterName::findOrFail($id);
        return view('blaster-name.add-edit', compact('blasterName'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'b_name' => 'required|string|max:255',
            'phone_no' => 'required|string|max:10|min:10|regex:/^[0-9+\-\s]+$/',
            'status' => 'required|in:active,inactive'
        ]);

        $blasterName = BlasterName::findOrFail($id);
        $blasterName->update($request->all());

        return redirect()->route('blaster-name.editIndex')->with('success', 'Blaster Name updated successfully.');
    }
}