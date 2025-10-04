<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Royalty;

class RoyaltyController extends Controller
{
    public function index(Request $request)
    {
        $query = Royalty::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $royalties = $query->latest()->paginate(5);

        $allNames = Royalty::select('name')->distinct()->pluck('name');
        $allDates = Royalty::select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('royalty.index', compact('royalties', 'allNames', 'allDates'))
                ->with('i', ($royalties->currentPage() - 1) * $royalties->perPage());
        }


        return view('royalty.index', compact('royalties', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('royalty.add-edit');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:royalties',
        ],[
            'name.required' => 'Royalty name is required',
        ]);
        $royalty = Royalty::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $royalty->id,
                'name' => $royalty->name,
            ]);
        }

        return redirect()->route('royalty.index')->with('success', 'Royalty added successfully');
    }

    public function edit($id)
    {
        $royalty = Royalty::findOrFail($id);
        return view('royalty.add-edit', compact('royalty'));
    }


    public function update(Request $request, $id)
    {
        $royalty = $request->validate([
            'name' => 'required|string|max:255',
        ],[
            'name.required' => 'Place name is required',
        ]);
        Royalty::find($id)->update($royalty);
        return redirect()->route('royalty.editIndex')->with('success', 'Royalty updated successfully');
    }
    
    public function show($id)
    {
        $royalties = Royalty::find($id);
        return view('royalty.show', compact('royalties'));
    }

    public function editIndex(Request $request)
    {
        $query = Royalty::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $royalties = $query->latest()->paginate(5);

        $allNames = Royalty::select('name')->distinct()->pluck('name');
        $allDates = Royalty::select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('royalty.edit-index', compact('royalties', 'allNames', 'allDates'))
                ->with('i', ($royalties->currentPage() - 1) * $royalties->perPage());
        }

        return view('royalty.edit-index', compact('royalties', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
}

