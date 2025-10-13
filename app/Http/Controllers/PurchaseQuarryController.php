<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\PurchaseQuarry;

class PurchaseQuarryController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseQuarry::query();

        $query->where('table_type', 'purchase');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $quarries = $query->latest()->paginate(5);

        $allNames = PurchaseQuarry::where('table_type', 'purchase')->select('name')->distinct()->pluck('name');
        $allDates = PurchaseQuarry::where('table_type', 'purchase')->select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('purchaseQuarry.index', compact('quarries', 'allNames', 'allDates'))
                ->with('i', ($quarries->currentPage() - 1) * $quarries->perPage());
        }


        return view('purchaseQuarry.index', compact('quarries', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('purchaseQuarry.add-edit');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([ 
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('purchase_quarries')->where(function ($query) {
                    $query->where('table_type', 'purchase');
                }),
            ],
        ],[
            'name.required' => 'Quarry name is required',
        ]);
        $validated['table_type'] = 'purchase';
        $quarry = PurchaseQuarry::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'id' => $quarry->id,
                'name' => $quarry->name,
            ]);
        }

        return redirect()->route('purchaseQuarry.index')->with('success', 'Quarry added successfully');
    }

    public function edit($id)
    {
        $quarry = PurchaseQuarry::findOrFail($id);
        return view('purchaseQuarry.add-edit', compact('quarry'));
    }


    public function update(Request $request, $id)
    {
        $quarry = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('purchase_quarries')->ignore($id)->where(function ($query) {
                    $query->where('table_type', 'purchase');
                }),
            ],
        ],[
            'name.required' => 'Quarry name is required',
        ]);
        $quarry['table_type'] = 'purchase';
        PurchaseQuarry::find($id)->update($quarry);
        return redirect()->route('purchaseQuarry.editIndex')->with('success', 'Quarry updated successfully');
    }
    
    public function show($id)
    {
        $quarries = PurchaseQuarry::find($id);
        return view('purchaseQuarry.show', compact('quarries'));
    }

    public function editIndex(Request $request)
    {
        $query = PurchaseQuarry::query();
        
        $query->where('table_type', 'purchase');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $quarries = $query->latest()->paginate(5);

        $allNames = PurchaseQuarry::where('table_type', 'purchase')->select('name')->distinct()->pluck('name');
        $allDates = PurchaseQuarry::where('table_type', 'purchase')->select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('purchaseQuarry.edit-index', compact('quarries', 'allNames', 'allDates'))
                ->with('i', ($quarries->currentPage() - 1) * $quarries->perPage());
        }

        return view('purchaseQuarry.edit-index', compact('quarries', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
}
