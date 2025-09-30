<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Places;

class PlacesController extends Controller
{
    public function index(Request $request)
    {
        $places = Places::latest()->paginate(5);
        return view('places.index', compact('places'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function editIndex(Request $request)
    {
        $places = Places::latest()->paginate(5);
        return view('places.edit-index' , compact('places'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function show($id)
    {
        $places = Places::find($id);
        return view('places.show', compact('places'));
    }

    public function create()
    {
        return view('places.add-edit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:places',
        ],[
            'name.required' => 'Place name is required',
        ]);
        $places = Places::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $places->id,
                'name' => $places->name,
            ]);
        }

        return redirect()->route('places.index')->with('suuccess', 'Places added successfully');
    }

    public function edit($id)
    {
        $places = Places::find($id);
        return view('places.add-edit', compact('places'));
    }

    public function update(Request $request, $id)
    {
        $places = $request->validate([
            'name' => 'required|string|max:255',
        ],[
            'name.required' => 'Place name is required',
        ]);
        Places::find($id)->update($places);
        return redirect()->route('places.editIndex')->with('success', 'Places updated successfully');
    }

    public function destroy($id)
    {
        Places::find($id)->delete();
        return redirect()->route('places.editIndex')->with('success', 'Places deleted successfully');
    }

}
