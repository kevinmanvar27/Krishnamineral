<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materials;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
        $data = Materials::latest()->paginate(5);
        return view('materials.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function editIndex(Request $request)
    {
        $materials = Materials::latest()->paginate(5);
        return view('materials.edit-index' , compact('materials'))
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
            'name' => 'required|string|max:255|unique:materials',
        ],[
            'name.required' => "Material is required",
        ]);

        $material = Materials::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $material->id,
                'name' => $material->name,
            ]);
        }

        return redirect()->route('materials.index')->with('success', 'Material added successfully');
    }


    public function edit($id)
    {
        $materials = Materials::find($id);
        return view('materials.add-edit', compact('materials'));
    }

    public function update(Request $request, $id)
    {
        $materials = $request->validate([
            'name' => 'required|string|max:255',
        ],[
            'name.required' => 'Material is required',
        ]);
        Materials::find($id)->update($materials);
        return redirect()->route('materials.editIndex')->with('success', 'Materials updated successfully');
    }

    public function destroy($id)
    {
        Materials::find($id)->delete();
        return redirect()->route('materials.editIndex')->with('success', 'Materials deleted successfully');
    }
}
