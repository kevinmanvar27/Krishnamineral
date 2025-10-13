<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Places;

class PlacesController extends Controller
{
    public function index(Request $request)
    {
        $query = Places::query();

        $query->where('table_type', 'sales');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $places = $query->latest()->paginate(5);

        $allNames = Places::select('name')->distinct()->pluck('name');
        $allDates = Places::select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('places.index', compact('places', 'allNames', 'allDates'))
                ->with('i', ($places->currentPage() - 1) * $places->perPage());
        }

        return view('places.index', compact('places', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function editIndex(Request $request)
    {
        $query = Places::query();
        
        $query->where('table_type', 'sales');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $places = $query->latest()->paginate(5);

        $allNames = Places::select('name')->distinct()->pluck('name');
        $allDates = Places::select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('places.edit-index', compact('places', 'allNames', 'allDates'))
                ->with('i', ($places->currentPage() - 1) * $places->perPage());
        }

        return view('places.edit-index' , compact('places', 'allNames', 'allDates'))
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
        $validated['table_type'] = 'sales';
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
        $places['table_type'] = 'sales';
        Places::find($id)->update($places);
        return redirect()->route('places.editIndex')->with('success', 'Places updated successfully');
    }

    public function destroy($id)
    {
        Places::find($id)->delete();
        return redirect()->route('places.editIndex')->with('success', 'Places deleted successfully');
    }

}
