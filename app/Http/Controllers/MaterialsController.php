<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Materials;
use Illuminate\Support\Facades\Auth;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
        $query = Materials::query();

        $query->where('table_type', 'sales');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $data = $query->latest()->paginate(5);

        $allNames = Materials::select('name')->distinct()->pluck('name');
        $allDates = Materials::select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('materials.index', compact('data', 'allNames', 'allDates'))
                ->with('i', ($data->currentPage() - 1) * $data->perPage());
        }

        return view('materials.index', compact('data', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function editIndex(Request $request)
    {
        $query = Materials::query();
        
        $query->where('table_type', 'sales');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $materials = $query->latest()->paginate(5);

        $allNames = Materials::select('name')->distinct()->pluck('name');
        $allDates = Materials::select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('materials.edit-index', compact('materials', 'allNames', 'allDates'))
                ->with('i', ($materials->currentPage() - 1) * $materials->perPage());
        }

        return view('materials.edit-index', compact('materials', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function show($id)
    {
        $materials = Materials::find($id);
        return view('materials.show', compact('materials'));
    }

    public function create()
    {
        return view('materials.add-edit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('materials')->where(function ($query) {
                    $query->where('table_type', 'sales');
                }),
            ],
        ],[
            'name.required' => "Material is required",
        ]);
        $validated['table_type'] = 'sales';
        $material = Materials::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $material->id,
                'name' => $material->name,
            ]);
        }

        return redirect()->route(Auth::user()->can('edit-materials') ? 'materials.editIndex' : 'home')->with('success', 'Material added successfully');
    }

    public function edit($id)
    {
        $materials = Materials::find($id);
        return view('materials.add-edit', compact('materials'));
    }

    public function update(Request $request, $id)
    {
        $materials = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('materials')->ignore($id)->where(function ($query) {
                    $query->where('table_type', 'sales');
                }),
            ],
        ],[
            'name.required' => 'Material is required',
        ]);
        $materials['table_type'] = 'sales';
        Materials::find($id)->update($materials);
        return redirect()->route(Auth::user()->can('edit-materials') ? 'materials.editIndex' : 'home')->with('success', 'Materials updated successfully');
    }

    public function destroy($id)
    {
        Materials::find($id)->delete();
        return redirect()->route(Auth::user()->can('edit-materials') ? 'materials.editIndex' : 'home')->with('success', 'Materials deleted successfully');
    }
}
