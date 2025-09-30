<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loading;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $loadings = Loading::latest()->paginate(5);
        return view('loading.index', compact('loadings'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('loading.add-edit');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:loadings',
        ], [
            'name.required' => 'Loading name is required',
        ]);

        $loading = Loading::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $loading->id,
                'name' => $loading->name,
            ]);
        }

        // Fallback for normal form submission
        return redirect()->route('loading.index')->with('success', 'Loading added successfully');
    }


    public function edit($id)
    {
        $loading = Loading::findOrFail($id);
        return view('loading.add-edit', compact('loading'));
    }


    public function update(Request $request, $id)
    {
        $loading = $request->validate([
            'name' => 'required|string|max:255',
        ],[
            'name.required' => 'Loading name is required',
        ]);
        Loading::find($id)->update($loading);
        return redirect()->route('loading.editIndex')->with('success', 'Loading updated successfully');
    }
    
    public function show($id)
    {
        $loadings = Loading::find($id);
        return view('loading.show', compact('loadings'));
    }

    public function editIndex(Request $request)
    {
        $loadings = Loading::latest()->paginate(5);
        return view('loading.edit-index', compact('loadings'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
}
