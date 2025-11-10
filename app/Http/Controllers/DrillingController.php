<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Drilling;
use App\Models\DrillingName;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DrillingController extends Controller
{
    public function index(Request $request)
    {
        $query = Drilling::query();

        // Add filters
        if ($request->challan) {
            // Extract the numeric part from the challan filter (D_123 -> 123)
            $challanId = str_replace('D_', '', $request->challan);
            $query->where('drilling_id', $challanId);
        }

        if ($request->drilling_name) {
            $query->where('dri_id', $request->drilling_name);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('date_time', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            $query->whereDate('date_time', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->whereDate('date_time', '<=', $request->date_to);
        }

        if ($request->notes) {
            $query->where('d_notes', 'like', '%' . $request->notes . '%');
        }

        // Add hole filters (JSON data)
        if ($request->hole_name) {
            $query->where('hole', 'like', '%"name":"' . $request->hole_name . '"%');
        }

        if ($request->hole_foot) {
            $query->where('hole', 'like', '%"foot":"' . $request->hole_foot . '"%');
        }

        if ($request->hole_rate) {
            $query->where('hole', 'like', '%"rate":"' . $request->hole_rate . '"%');
        }

        if ($request->hole_total) {
            $query->where('hole', 'like', '%"total":"' . $request->hole_total . '"%');
        }

        if ($request->gross_total) {
            $query->where('gross_total', $request->gross_total);
        }

        $drillings = $query->with('drillingName')->latest()->paginate(5);

        $allDrillingNames = DrillingName::all();
        $allNotes = Drilling::select('d_notes')->distinct()->pluck('d_notes');
        
        // Get distinct values for hole fields from JSON data
        $allHoleNames = Drilling::select('hole')
            ->get()
            ->flatMap(function ($drilling) {
                // The hole attribute is already decoded by the model accessor
                $holeData = $drilling->hole;
                if (is_array($holeData)) {
                    return collect($holeData)->pluck('name')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allHoleFoots = Drilling::select('hole')
            ->get()
            ->flatMap(function ($drilling) {
                // The hole attribute is already decoded by the model accessor
                $holeData = $drilling->hole;
                if (is_array($holeData)) {
                    return collect($holeData)->pluck('foot')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allHoleRates = Drilling::select('hole')
            ->get()
            ->flatMap(function ($drilling) {
                // The hole attribute is already decoded by the model accessor
                $holeData = $drilling->hole;
                if (is_array($holeData)) {
                    return collect($holeData)->pluck('rate')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allHoleTotals = Drilling::select('hole')
            ->get()
            ->flatMap(function ($drilling) {
                // The hole attribute is already decoded by the model accessor
                $holeData = $drilling->hole;
                if (is_array($holeData)) {
                    return collect($holeData)->pluck('total')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allGrossTotals = Drilling::select('gross_total')->distinct()->pluck('gross_total');
        
        // Get all distinct drilling IDs for challan filter
        $allChallans = Drilling::select('drilling_id')->distinct()->pluck('drilling_id');

        if ($request->ajax()) {
            return view('drilling.index', compact('drillings', 'allDrillingNames', 'allNotes', 'allHoleNames', 'allHoleFoots', 'allHoleRates', 'allHoleTotals', 'allGrossTotals', 'allChallans'))
                ->with('i', ($drillings->currentPage() - 1) * $drillings->perPage());
        }

        return view('drilling.index', compact('drillings', 'allDrillingNames', 'allNotes', 'allHoleNames', 'allHoleFoots', 'allHoleRates', 'allHoleTotals', 'allGrossTotals', 'allChallans'))
            ->with('i', ($drillings->currentPage() - 1) * $drillings->perPage());
    }

    public function editIndex(Request $request)
    {
        $query = Drilling::query();

        // Add filters
        if ($request->challan) {
            // Extract the numeric part from the challan filter (D_123 -> 123)
            $challanId = str_replace('D_', '', $request->challan);
            $query->where('drilling_id', $challanId);
        }

        if ($request->drilling_name) {
            $query->where('dri_id', $request->drilling_name);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('date_time', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            $query->whereDate('date_time', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->whereDate('date_time', '<=', $request->date_to);
        }

        if ($request->notes) {
            $query->where('d_notes', 'like', '%' . $request->notes . '%');
        }

        // Add hole filters (JSON data)
        if ($request->hole_name) {
            $query->where('hole', 'like', '%"name":"' . $request->hole_name . '"%');
        }

        if ($request->hole_foot) {
            $query->where('hole', 'like', '%"foot":"' . $request->hole_foot . '"%');
        }

        if ($request->hole_rate) {
            $query->where('hole', 'like', '%"rate":"' . $request->hole_rate . '"%');
        }

        if ($request->hole_total) {
            $query->where('hole', 'like', '%"total":"' . $request->hole_total . '"%');
        }

        if ($request->gross_total) {
            $query->where('gross_total', $request->gross_total);
        }

        $drillings = $query->with('drillingName')->latest()->paginate(5);

        $allDrillingNames = DrillingName::all();
        $allNotes = Drilling::select('d_notes')->distinct()->pluck('d_notes');
        
        // Get distinct values for hole fields from JSON data
        $allHoleNames = Drilling::select('hole')
            ->get()
            ->flatMap(function ($drilling) {
                // The hole attribute is already decoded by the model accessor
                $holeData = $drilling->hole;
                if (is_array($holeData)) {
                    return collect($holeData)->pluck('name')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allHoleFoots = Drilling::select('hole')
            ->get()
            ->flatMap(function ($drilling) {
                // The hole attribute is already decoded by the model accessor
                $holeData = $drilling->hole;
                if (is_array($holeData)) {
                    return collect($holeData)->pluck('foot')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allHoleRates = Drilling::select('hole')
            ->get()
            ->flatMap(function ($drilling) {
                // The hole attribute is already decoded by the model accessor
                $holeData = $drilling->hole;
                if (is_array($holeData)) {
                    return collect($holeData)->pluck('rate')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allHoleTotals = Drilling::select('hole')
            ->get()
            ->flatMap(function ($drilling) {
                // The hole attribute is already decoded by the model accessor
                $holeData = $drilling->hole;
                if (is_array($holeData)) {
                    return collect($holeData)->pluck('total')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allGrossTotals = Drilling::select('gross_total')->distinct()->pluck('gross_total');
        
        // Get all distinct drilling IDs for challan filter
        $allChallans = Drilling::select('drilling_id')->distinct()->pluck('drilling_id');

        if ($request->ajax()) {
            return view('drilling.edit-index', compact('drillings', 'allDrillingNames', 'allNotes', 'allHoleNames', 'allHoleFoots', 'allHoleRates', 'allHoleTotals', 'allGrossTotals', 'allChallans'))
                ->with('i', ($drillings->currentPage() - 1) * $drillings->perPage());
        }

        return view('drilling.edit-index', compact('drillings', 'allDrillingNames', 'allNotes', 'allHoleNames', 'allHoleFoots', 'allHoleRates', 'allHoleTotals', 'allGrossTotals', 'allChallans'))
            ->with('i', ($drillings->currentPage() - 1) * $drillings->perPage());
    }

    public function create(Drilling $drilling)
    {
        $latestDrilling = Drilling::latest('drilling_id')->first();
        $drillingNames = DrillingName::where('status', 1)->get();
        return view('drilling.add-edit', compact('latestDrilling' ,'drillingNames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dri_id' => 'required|exists:drilling_names,dri_id',
            'd_notes' => 'nullable|string|max:255',
            'date_time' => 'required|date',
            'hole' => 'required|array',
            'hole_foot' => 'required|array',
            'hole_rate' => 'required|array',
            'hole_total' => 'required|array',
            'gross_total' => 'required|numeric',
        ]);
        
        // Prepare hole data as JSON
        $holeData = [];
        foreach ($request->hole as $index => $hole) {
            if (!empty($hole) || !empty($request->hole_foot[$index])) {
                $holeData[] = [
                    'name' => $hole,
                    'foot' => $request->hole_foot[$index] ?? '',
                    'rate' => $request->hole_rate[$index] ?? '',
                    'total' => $request->hole_total[$index] ?? ''
                ];
            }
        }
        
        $drilling = new Drilling();
        $drilling->dri_id = $request->dri_id;
        $drilling->d_notes = $request->d_notes;
        $drilling->date_time = $request->date_time;
        $drilling->hole = $holeData;
        $drilling->gross_total = $request->gross_total;
        $drilling->status = 0; // Default status
        $drilling->update_by = Auth::id(); // Current user
        $drilling->save();

        // Save ID in session for PDF auto-download
        session([
            'pdf_drilling_id' => $drilling->drilling_id,
            'auto_download_pdf' => true
        ]);

        return redirect()->route('drilling.index')->with('success', 'Drilling created successfully.');
    }

    public function show($id)
    {
        $drilling = Drilling::with('drillingName')->findOrFail($id);
        return view('drilling.show', compact('drilling'));
    }

    public function edit($id)
    {
        $drilling = Drilling::findOrFail($id);
        $drillingNames = DrillingName::where('status', 1)->get();
        return view('drilling.add-edit', compact('drilling', 'drillingNames'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dri_id' => 'required|exists:drilling_names,dri_id',
            'd_notes' => 'nullable|string|max:255',
            'date_time' => 'required|date',
            'hole' => 'required|array',
            'hole_foot' => 'required|array',
            'hole_rate' => 'required|array',
            'hole_total' => 'required|array',
            'gross_total' => 'required|numeric',
        ]);
        
        // Prepare hole data as JSON
        $holeData = [];
        foreach ($request->hole as $index => $hole) {
            if (!empty($hole) || !empty($request->hole_foot[$index])) {
                $holeData[] = [
                    'name' => $hole,
                    'foot' => $request->hole_foot[$index] ?? '',
                    'rate' => $request->hole_rate[$index] ?? '',
                    'total' => $request->hole_total[$index] ?? ''
                ];
            }
        }
        
        $drilling = Drilling::findOrFail($id);
        $drilling->dri_id = $request->dri_id;
        $drilling->d_notes = $request->d_notes;
        $drilling->date_time = $request->date_time;
        $drilling->hole = $holeData;
        $drilling->gross_total = $request->gross_total;
        $drilling->update_by = Auth::id(); // Current user
        $drilling->save();

        // Save ID in session for PDF auto-download
        session([
            'pdf_drilling_id' => $drilling->drilling_id,
            'auto_download_pdf' => true
        ]);

        return redirect()->route('drilling.editIndex')->with('success', 'Drilling updated successfully.');
    }

    public function drillingPdf($id)
    {
        $drilling = Drilling::with('drillingName')->findOrFail($id);

        $pdfData = [
            'challan_number' => $drilling->drilling_id,
            'date_time' => $drilling->date_time,
            'drilling_name' => $drilling->drillingName->d_name ?? 'N/A',
            'hole_data' => $drilling->hole,
            'gross_total' => $drilling->gross_total,
        ];

        $pdf = Pdf::loadView('drilling.pdf', $pdfData);

        return $pdf->download("Drilling.pdf");
    }
}