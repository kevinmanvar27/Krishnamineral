<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loading;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $query = Loading::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $loadings = $query->latest()->paginate(5);

        $allNames = Loading::select('name')->distinct()->pluck('name');
        $allDates = Loading::select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('loading.index', compact('loadings', 'allNames', 'allDates'))
                ->with('i', ($loadings->currentPage() - 1) * $loadings->perPage());
        }

        return view('loading.index', compact('loadings', 'allNames', 'allDates'))
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
        $query = Loading::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $loadings = $query->latest()->paginate(5);

        $allNames = Loading::select('name')->distinct()->pluck('name');
        $allDates = Loading::select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('loading.edit-index', compact('loadings', 'allNames', 'allDates'))
                ->with('i', ($loadings->currentPage() - 1) * $loadings->perPage());
        }

        return view('loading.edit-index', compact('loadings', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
}
