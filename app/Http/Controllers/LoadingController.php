<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Loading;
use Illuminate\Support\Facades\Auth;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $query = Loading::query();

        $query->where('table_type', 'sales');

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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('loadings')->where(function ($query) {
                    $query->where('table_type', 'sales');
                }),
            ],
        ], [
            'name.required' => 'Loading name is required',
        ]);
        $validated['table_type'] = 'sales';
        $loading = Loading::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $loading->id,
                'name' => $loading->name,
            ]);
        }

        // Fallback for normal form submission
        return redirect()->route(Auth::user()->can('edit-loading') ? 'loading.editIndex' : 'home')->with('success', 'Loading added successfully');
    }


    public function edit($id)
    {
        $loading = Loading::findOrFail($id);
        return view('loading.add-edit', compact('loading'));
    }


    public function update(Request $request, $id)
    {
        $loading = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('loadings')->ignore($id)->where(function ($query) {
                    $query->where('table_type', 'sales');
                }),
            ],
        ],[
            'name.required' => 'Loading name is required',
        ]);
        $loading['table_type'] = 'sales';
        Loading::find($id)->update($loading);
        return redirect()->route(Auth::user()->can('edit-loading') ? 'loading.editIndex' : 'home')->with('success', 'Loading updated successfully');
    }
    
    public function show($id)
    {
        $loadings = Loading::find($id);
        return view('loading.show', compact('loadings'));
    }

    public function editIndex(Request $request)
    {
        $query = Loading::query();

        $query->where('table_type', 'sales');

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
