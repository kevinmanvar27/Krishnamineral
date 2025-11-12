<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blasting;
use App\Models\BlasterName;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BlastingController extends Controller
{
    public function index(Request $request)
    {
        $query = Blasting::query();

        // Add filters
        if ($request->blaster_name) {
            $query->where('bnm_id', $request->blaster_name);
        }

        if ($request->challan) {
            $query->where('blasting_id', $request->challan);
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
            $query->where('b_notes', 'like', '%' . $request->notes . '%');
        }

        // Add column filters
        if ($request->geliten) {
            $query->where('geliten', $request->geliten);
        }

        if ($request->geliten_rate) {
            $query->where('geliten_rate', $request->geliten_rate);
        }

        if ($request->geliten_total) {
            $query->where('geliten_total', $request->geliten_total);
        }

        if ($request->df) {
            $query->where('df', $request->df);
        }

        if ($request->df_rate) {
            $query->where('df_rate', $request->df_rate);
        }

        if ($request->df_total) {
            $query->where('df_total', $request->df_total);
        }

        if ($request->odvat) {
            $query->where('odvat', $request->odvat);
        }

        if ($request->od_rate) {
            $query->where('od_rate', $request->od_rate);
        }

        if ($request->od_total) {
            $query->where('od_total', $request->od_total);
        }

        if ($request->gross_total) {
            $query->where('gross_total', $request->gross_total);
        }

        // Add controll filters (JSON data)
        if ($request->controll_name) {
            $query->where('controll', 'like', '%"name":"' . $request->controll_name . '"%');
        }

        if ($request->controll_meter) {
            $query->where('controll', 'like', '%"meter":"' . $request->controll_meter . '"%');
        }

        if ($request->controll_rate) {
            $query->where('controll', 'like', '%"rate":"' . $request->controll_rate . '"%');
        }

        if ($request->controll_total) {
            $query->where('controll', 'like', '%"total":"' . $request->controll_total . '"%');
        }

        $blastings = $query->with('blasterName')->latest()->paginate(5);

        $allBlasterNames = BlasterName::all();
        $allNotes = Blasting::select('b_notes')->distinct()->pluck('b_notes');
        
        // Get distinct values for filter dropdowns
        $allGeliten = Blasting::select('geliten')->distinct()->pluck('geliten');
        $allGelitenRates = Blasting::select('geliten_rate')->distinct()->pluck('geliten_rate');
        $allGelitenTotals = Blasting::select('geliten_total')->distinct()->pluck('geliten_total');
        $allDfs = Blasting::select('df')->distinct()->pluck('df');
        $allDfRates = Blasting::select('df_rate')->distinct()->pluck('df_rate');
        $allDfTotals = Blasting::select('df_total')->distinct()->pluck('df_total');
        $allOdvats = Blasting::select('odvat')->distinct()->pluck('odvat');
        $allOdRates = Blasting::select('od_rate')->distinct()->pluck('od_rate');
        $allOdTotals = Blasting::select('od_total')->distinct()->pluck('od_total');
        $allGrossTotals = Blasting::select('gross_total')->distinct()->pluck('gross_total');
        // Get distinct challan numbers
        $allChallans = Blasting::select('blasting_id')->distinct()->pluck('blasting_id');
        
        // Get distinct values for controll fields from JSON data
        $allControllNames = Blasting::select('controll')
            ->get()
            ->flatMap(function ($blasting) {
                // The controll attribute is already decoded by the model accessor
                $controllData = $blasting->controll;
                if (is_array($controllData)) {
                    return collect($controllData)->pluck('name')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allControllMeters = Blasting::select('controll')
            ->get()
            ->flatMap(function ($blasting) {
                // The controll attribute is already decoded by the model accessor
                $controllData = $blasting->controll;
                if (is_array($controllData)) {
                    return collect($controllData)->pluck('meter')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allControllRates = Blasting::select('controll')
            ->get()
            ->flatMap(function ($blasting) {
                // The controll attribute is already decoded by the model accessor
                $controllData = $blasting->controll;
                if (is_array($controllData)) {
                    return collect($controllData)->pluck('rate')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allControllTotals = Blasting::select('controll')
            ->get()
            ->flatMap(function ($blasting) {
                // The controll attribute is already decoded by the model accessor
                $controllData = $blasting->controll;
                if (is_array($controllData)) {
                    return collect($controllData)->pluck('total')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();

        if ($request->ajax()) {
            return view('blasting.index', compact('blastings', 'allBlasterNames', 'allNotes', 'allGeliten', 'allGelitenRates', 'allGelitenTotals', 'allDfs', 'allDfRates', 'allDfTotals', 'allOdvats', 'allOdRates', 'allOdTotals', 'allGrossTotals', 'allControllNames', 'allControllMeters', 'allControllRates', 'allControllTotals', 'allChallans'))
                ->with('i', ($blastings->currentPage() - 1) * $blastings->perPage());
        }

        return view('blasting.index', compact('blastings', 'allBlasterNames', 'allNotes', 'allGeliten', 'allGelitenRates', 'allGelitenTotals', 'allDfs', 'allDfRates', 'allDfTotals', 'allOdvats', 'allOdRates', 'allOdTotals', 'allGrossTotals', 'allControllNames', 'allControllMeters', 'allControllRates', 'allControllTotals', 'allChallans'));
    }

    public function editIndex(Request $request)
    {
        $query = Blasting::query();

        // Add filters
        if ($request->blaster_name) {
            $query->where('bnm_id', $request->blaster_name);
        }

        if ($request->challan) {
            $query->where('blasting_id', $request->challan);
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
            $query->where('b_notes', 'like', '%' . $request->notes . '%');
        }

        // Add column filters
        if ($request->geliten) {
            $query->where('geliten', $request->geliten);
        }

        if ($request->geliten_rate) {
            $query->where('geliten_rate', $request->geliten_rate);
        }

        if ($request->geliten_total) {
            $query->where('geliten_total', $request->geliten_total);
        }

        if ($request->df) {
            $query->where('df', $request->df);
        }

        if ($request->df_rate) {
            $query->where('df_rate', $request->df_rate);
        }

        if ($request->df_total) {
            $query->where('df_total', $request->df_total);
        }

        if ($request->odvat) {
            $query->where('odvat', $request->odvat);
        }

        if ($request->od_rate) {
            $query->where('od_rate', $request->od_rate);
        }

        if ($request->od_total) {
            $query->where('od_total', $request->od_total);
        }

        if ($request->gross_total) {
            $query->where('gross_total', $request->gross_total);
        }

        // Add controll filters (JSON data)
        if ($request->controll_name) {
            $query->where('controll', 'like', '%"name":"' . $request->controll_name . '"%');
        }

        if ($request->controll_meter) {
            $query->where('controll', 'like', '%"meter":"' . $request->controll_meter . '"%');
        }

        if ($request->controll_rate) {
            $query->where('controll', 'like', '%"rate":"' . $request->controll_rate . '"%');
        }

        if ($request->controll_total) {
            $query->where('controll', 'like', '%"total":"' . $request->controll_total . '"%');
        }

        $blastings = $query->with('blasterName')->latest()->paginate(5);

        $allBlasterNames = BlasterName::all();
        $allNotes = Blasting::select('b_notes')->distinct()->pluck('b_notes');
        
        // Get distinct values for filter dropdowns
        $allGeliten = Blasting::select('geliten')->distinct()->pluck('geliten');
        $allGelitenRates = Blasting::select('geliten_rate')->distinct()->pluck('geliten_rate');
        $allGelitenTotals = Blasting::select('geliten_total')->distinct()->pluck('geliten_total');
        $allDfs = Blasting::select('df')->distinct()->pluck('df');
        $allDfRates = Blasting::select('df_rate')->distinct()->pluck('df_rate');
        $allDfTotals = Blasting::select('df_total')->distinct()->pluck('df_total');
        $allOdvats = Blasting::select('odvat')->distinct()->pluck('odvat');
        $allOdRates = Blasting::select('od_rate')->distinct()->pluck('od_rate');
        $allOdTotals = Blasting::select('od_total')->distinct()->pluck('od_total');
        $allGrossTotals = Blasting::select('gross_total')->distinct()->pluck('gross_total');
        
        // Get distinct challan numbers
        $allChallans = Blasting::select('blasting_id')->distinct()->pluck('blasting_id');
        
        // Get distinct values for controll fields from JSON data
        $allControllNames = Blasting::select('controll')
            ->get()
            ->flatMap(function ($blasting) {
                // The controll attribute is already decoded by the model accessor
                $controllData = $blasting->controll;
                if (is_array($controllData)) {
                    return collect($controllData)->pluck('name')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allControllMeters = Blasting::select('controll')
            ->get()
            ->flatMap(function ($blasting) {
                // The controll attribute is already decoded by the model accessor
                $controllData = $blasting->controll;
                if (is_array($controllData)) {
                    return collect($controllData)->pluck('meter')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allControllRates = Blasting::select('controll')
            ->get()
            ->flatMap(function ($blasting) {
                // The controll attribute is already decoded by the model accessor
                $controllData = $blasting->controll;
                if (is_array($controllData)) {
                    return collect($controllData)->pluck('rate')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();
            
        $allControllTotals = Blasting::select('controll')
            ->get()
            ->flatMap(function ($blasting) {
                // The controll attribute is already decoded by the model accessor
                $controllData = $blasting->controll;
                if (is_array($controllData)) {
                    return collect($controllData)->pluck('total')->filter();
                }
                return collect();
            })
            ->unique()
            ->values();

        if ($request->ajax()) {
            return view('blasting.edit-index', compact('blastings', 'allBlasterNames', 'allNotes', 'allGeliten', 'allGelitenRates', 'allGelitenTotals', 'allDfs', 'allDfRates', 'allDfTotals', 'allOdvats', 'allOdRates', 'allOdTotals', 'allGrossTotals', 'allControllNames', 'allControllMeters', 'allControllRates', 'allControllTotals', 'allChallans'))
                ->with('i', ($blastings->currentPage() - 1) * $blastings->perPage());
        }

        return view('blasting.edit-index', compact('blastings', 'allBlasterNames', 'allNotes', 'allGeliten', 'allGelitenRates', 'allGelitenTotals', 'allDfs', 'allDfRates', 'allDfTotals', 'allOdvats', 'allOdRates', 'allOdTotals', 'allGrossTotals', 'allControllNames', 'allControllMeters', 'allControllRates', 'allControllTotals', 'allChallans'));
    }

    public function create(Blasting $blasting)
    {
        $latestBlasting = Blasting::latest('blasting_id')->first(); // Changed from Blasting::latest()->first();
        $blasterNames = BlasterName::where('status', 1)->get();
        return view('blasting.add-edit', compact('latestBlasting' ,'blasterNames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bnm_id' => 'required|exists:blaster_names,bnm_id',
            'b_notes' => 'nullable|string|max:255',
            'date_time' => 'required|date',
            'geliten' => 'required|numeric',
            'geliten_rate' => 'required|numeric',
            'geliten_total' => 'required|numeric',
            'df' => 'required|numeric',
            'df_rate' => 'required|numeric',
            'df_total' => 'required|numeric',
            'odvat' => 'required|numeric',
            'od_rate' => 'required|numeric',
            'od_total' => 'required|numeric',
            'controll' => 'required|array',
            'controll_meter' => 'required|array',
            'controll_rate' => 'required|array',
            'controll_total' => 'required|array',
            'gross_total' => 'required|numeric',
        ]);
        
        // Prepare controll data as JSON
        $controllData = [];
        foreach ($request->controll as $index => $controll) {
            if (!empty($controll) || !empty($request->controll_meter[$index])) {
                $controllData[] = [
                    'name' => $controll,
                    'meter' => $request->controll_meter[$index] ?? '',
                    'rate' => $request->controll_rate[$index] ?? '',
                    'total' => $request->controll_total[$index] ?? ''
                ];
            }
        }
        
        $blasting = new Blasting();
        $blasting->bnm_id = $request->bnm_id;
        $blasting->b_notes = $request->b_notes;
        $blasting->date_time = $request->date_time;
        $blasting->geliten = $request->geliten;
        $blasting->geliten_rate = $request->geliten_rate;
        $blasting->geliten_total = $request->geliten_total;
        $blasting->df = $request->df;
        $blasting->df_rate = $request->df_rate;
        $blasting->df_total = $request->df_total;
        $blasting->odvat = $request->odvat;
        $blasting->od_rate = $request->od_rate;
        $blasting->od_total = $request->od_total;
        $blasting->controll = $controllData;
        $blasting->gross_total = $request->gross_total;
        $blasting->status = 0; // Default status
        $blasting->update_by = Auth::id(); // Current user
        $blasting->save();

        // Save ID in session for PDF auto-download
        session([
            'pdf_blasting_id' => $blasting->blasting_id,
            'auto_download_pdf' => true
        ]);

        return redirect()->route(Auth::user()->can('edit-blasting') ? 'blasting.editIndex' : 'home')->with('success', 'Blasting created successfully.');
    }

    public function show($id)
    {
        $blasting = Blasting::with('blasterName')->findOrFail($id);
        return view('blasting.show', compact('blasting'));
    }

    public function edit($id)
    {
        $blasting = Blasting::findOrFail($id);
        $blasterNames = BlasterName::where('status', 1)->get();
        return view('blasting.add-edit', compact('blasting', 'blasterNames'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bnm_id' => 'required|exists:blaster_names,bnm_id',
            'b_notes' => 'nullable|string|max:255',
            'date_time' => 'required|date',
            'geliten' => 'required|numeric',
            'geliten_rate' => 'required|numeric',
            'geliten_total' => 'required|numeric',
            'df' => 'required|numeric',
            'df_rate' => 'required|numeric',
            'df_total' => 'required|numeric',
            'odvat' => 'required|numeric',
            'od_rate' => 'required|numeric',
            'od_total' => 'required|numeric',
            'controll' => 'required|array',
            'controll_meter' => 'required|array',
            'controll_rate' => 'required|array',
            'controll_total' => 'required|array',
            'gross_total' => 'required|numeric',
        ]);
        
        // Prepare controll data as JSON
        $controllData = [];
        foreach ($request->controll as $index => $controll) {
            if (!empty($controll) || !empty($request->controll_meter[$index])) {
                $controllData[] = [
                    'name' => $controll,
                    'meter' => $request->controll_meter[$index] ?? '',
                    'rate' => $request->controll_rate[$index] ?? '',
                    'total' => $request->controll_total[$index] ?? ''
                ];
            }
        }
        
        $blasting = Blasting::findOrFail($id);
        $blasting->bnm_id = $request->bnm_id;
        $blasting->b_notes = $request->b_notes;
        $blasting->date_time = $request->date_time;
        $blasting->geliten = $request->geliten;
        $blasting->geliten_rate = $request->geliten_rate;
        $blasting->geliten_total = $request->geliten_total;
        $blasting->df = $request->df;
        $blasting->df_rate = $request->df_rate;
        $blasting->df_total = $request->df_total;
        $blasting->odvat = $request->odvat;
        $blasting->od_rate = $request->od_rate;
        $blasting->od_total = $request->od_total;
        $blasting->controll = $controllData;
        $blasting->gross_total = $request->gross_total;
        $blasting->update_by = Auth::id(); // Current user
        $blasting->save();

        // Save ID in session for PDF auto-download
        session([
            'pdf_blasting_id' => $blasting->blasting_id,
            'auto_download_pdf' => true
        ]);

        return redirect()->route(Auth::user()->can('edit-blasting') ? 'blasting.editIndex' : 'home')->with('success', 'Blasting updated successfully.');
    }

    public function blastingPdf($id)
    {
        $blasting = Blasting::with('blasterName')->findOrFail($id);

        $pdfData = [
            'challan_number' => $blasting->blasting_id,
            'date_time' => $blasting->date_time,
            'blaster_name' => $blasting->blasterName->b_name ?? 'N/A',
            'geliten' => $blasting->geliten,
            'geliten_rate' => $blasting->geliten_rate,
            'geliten_total' => $blasting->geliten_total,
            'df' => $blasting->df,
            'df_rate' => $blasting->df_rate,
            'df_total' => $blasting->df_total,
            'odvat' => $blasting->odvat,
            'od_rate' => $blasting->od_rate,
            'od_total' => $blasting->od_total,
            'controll_data' => $blasting->controll,
            'gross_total' => $blasting->gross_total,
        ];

        $pdf = Pdf::loadView('blasting.pdf', $pdfData);

        return $pdf->download("Blasting.pdf");
    }
}