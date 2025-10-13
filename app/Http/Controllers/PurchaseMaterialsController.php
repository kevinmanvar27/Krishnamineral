<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Materials;

class PurchaseMaterialsController extends Controller
{
    public function index(Request $request)
    {
        $query = Materials::query();

        $query->where('table_type', 'purchase');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $data = $query->latest()->paginate(5);

        $allNames = Materials::where('table_type', 'purchase')->select('name')->distinct()->pluck('name');
        $allDates = Materials::where('table_type', 'purchase')->select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('purchaseMaterials.index', compact('data', 'allNames', 'allDates'))
                ->with('i', ($data->currentPage() - 1) * $data->perPage());
        }

        return view('purchaseMaterials.index', compact('data', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function editIndex(Request $request)
    {
        $query = Materials::query();
        
        $query->where('table_type', 'purchase');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $materials = $query->latest()->paginate(5);

        $allNames = Materials::where('table_type', 'purchase')->select('name')->distinct()->pluck('name');
        $allDates = Materials::where('table_type', 'purchase')->select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('purchaseMaterials.edit-index', compact('materials', 'allNames', 'allDates'))
                ->with('i', ($materials->currentPage() - 1) * $materials->perPage());
        }

        return view('purchaseMaterials.edit-index', compact('materials', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function show($id)
    {
        $materials = Materials::find($id);
        return view('purchaseMaterials.show', compact('materials'));
    }

    public function create()
    {
        return view('purchaseMaterials.add-edit');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('materials')->where(function ($query) {
                    $query->where('table_type', 'purchase');
                }),
            ],
        ],[
            'name.required' => "Material is required",
        ]);
        $validated['table_type'] = 'purchase';
        $material = Materials::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $material->id,
                'name' => $material->name,
            ]);
        }

        return redirect()->route('purchaseMaterials.index')->with('success', 'Material added successfully');
    }


    public function edit($id)
    {
        $materials = Materials::find($id);
        return view('purchaseMaterials.add-edit', compact('materials'));
    }

    public function update(Request $request, $id)
    {
        $materials = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('materials')->ignore($id)->where(function ($query) {
                    $query->where('table_type', 'purchase');
                }),
            ],
        ],[
            'name.required' => 'Material is required',
        ]);
        $materials['table_type'] = 'purchase';
        Materials::find($id)->update($materials);
        return redirect()->route('purchaseMaterials.editIndex')->with('success', 'Materials updated successfully');
    }

    public function destroy($id)
    {
        Materials::find($id)->delete();
        return redirect()->route('purchaseMaterials.editIndex')->with('success', 'Materials deleted successfully');
    }

}
