<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Purchase;
use App\Models\Materials;
use App\Models\Loading;
use App\Models\Places;
use App\Models\Party;
use App\Models\Royalty;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sales::query();

        if ($request->transporter) {
            $query->where('transporter', 'like', '%' . $request->transporter . '%');
        }

        if ($request->contact_number) {
            $query->where('contact_number', 'like', '%' . $request->contact_number . '%');
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $query->latest()->paginate(5);

        $allDates = Sales::select('created_at')
            ->distinct()
            ->get()
            ->map(fn($d) => $d->created_at->format('Y-m-d'));
        $allTransporters = Sales::select('transporter')->distinct()->pluck('transporter');
        $allContacts = Sales::select('contact_number')->distinct()->pluck('contact_number');

        if ($request->ajax()) {
            return view('sales.index', compact('sales', 'allTransporters', 'allDates', 'allContacts'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage());
        }

        return view('sales.index', compact('sales', 'allTransporters', 'allDates', 'allContacts'));    
    }

    public function editIndex(Request $request)
    {
        $query = Sales::query();

        if ($request->transporter) {
            $query->where('transporter', 'like', '%' . $request->transporter . '%');
        }

        if ($request->challan) {
            $query->where('id', 'like', '%' . $request->challan . '%');
        }

        if ($request->contact_number) {
            $query->where('contact_number', 'like', '%' . $request->contact_number . '%');
        }

        if ($request->vehicle) {
            $query->where('vehicle_id', 'like', '%' . $request->vehicle . '%');
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $query->with('vehicle')
            ->where('status', 1)
            ->latest()
            ->paginate(5);

        $allDates = Sales::select('created_at')
            ->distinct()
            ->get()
            ->map(fn($d) => $d->created_at->format('Y-m-d'));   
        $allTransporters = Sales::where('status', 1)->select('transporter')->distinct()->pluck('transporter');
        $allContacts = Sales::where('status', 1)->select('contact_number')->distinct()->pluck('contact_number');
        $allChallans = Sales::where('status', 1)->select('id')->distinct()->pluck('id');
        $allVehicles = Vehicle::whereIn('id', Sales::where('status', 1)->distinct()->pluck('vehicle_id'))->get();

        if ($request->ajax()) {
            return view('sales.edit-index', compact('sales', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage());
        }
        return view('sales.edit-index', compact('sales', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        
    }

    public function create(Sales $sales)
    {
        $sales = Sales::latest('id')->first();
        $vehicles = Vehicle::where('table_type', 'sales')->get();
        return view('sales.create-sales', compact('sales', 'vehicles'));    
    }

    public function store(Request $request)
    { 
        $request->validate([
            'date_time' => 'required',
            'vehicle_id' => 'required',
            'transporter' => 'required',
            'tare_weight' => 'required',
            'contact_number' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'driver_contact_number' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
        ]);
        Sales::create($request->all());
        return redirect()->route('sales.pendingLoad')
            ->with('success', 'Sales created successfully.');
    }

    public function pendingLoad(Request $request)
    {
        $query = Sales::query();

        if ($request->transporter) {
            $query->where('transporter', 'like', '%' . $request->transporter . '%');
        }

        if ($request->challan) {
            $query->where('id', 'like', '%' . $request->challan . '%');
        }

        if ($request->contact_number) {
            $query->where('contact_number', 'like', '%' . $request->contact_number . '%');
        }

        if ($request->vehicle) {
            $query->where('vehicle_id', 'like', '%' . $request->vehicle . '%');
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }


        $sales = $query->with('vehicle')
            ->where('status', 0)
            ->latest()
            ->paginate(5);

        $allDates = Sales::select('created_at')
            ->distinct()
            ->get()
            ->map(fn($d) => $d->created_at->format('Y-m-d'));   
        $allTransporters = Sales::where('status', 0)->select('transporter')->distinct()->pluck('transporter');
        $allContacts = Sales::where('status', 0)->select('contact_number')->distinct()->pluck('contact_number');
        $allChallans = Sales::where('status', 0)->select('id')->distinct()->pluck('id');
        $allVehicles = Vehicle::whereIn('id', Sales::where('status', 0)->distinct()->pluck('vehicle_id'))->get();

        if ($request->ajax()) {
            return view('sales.pendingLoad-sales', compact('sales', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage());
        }

        return view('sales.pendingLoad-sales', compact('sales', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'));
    }

    public function show($id)
    {
        $sales = Sales::find($id);
        return view('sales.show', compact('sales'));
    }

    // New method to show sales details in modal
    public function showAjax($id)
    {
        $sales = Sales::with(['vehicle', 'material', 'loading', 'place', 'party', 'royalty', 'driver'])->findOrFail($id);
        return view('sales.modal-show', compact('sales'));
    }

    public function edit($id)
    {
        $sales = Sales::findOrFail($id);
        $vehicles = Vehicle::where('table_type', 'sales')->get();
        $materials = Materials::where('table_type', 'sales')->get();
        $loadings = Loading::where('table_type', 'sales')->get();
        $places = Places::where('table_type', 'sales')->get();
        $parties = Party::where('table_type', 'sales')->get();
        $royalties = Royalty::where('table_type', 'sales')->get();
        $drivers  = Driver::where('table_type', 'sales')->get();
        $employees = User::all();
        return view('sales.edit-sales', compact('sales', 'vehicles', 'materials', 'loadings', 'places', 'parties', 'royalties', 'drivers', 'employees'));    
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date_time' => 'required',
            'vehicle_id' => 'required',
            'transporter' => 'required',
            'tare_weight' => 'required',
            'contact_number' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'driver_contact_number' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'gross_weight' => 'required',
            'tare_weight' => 'required',
            'net_weight' => 'required',
            'material_id' => 'required|exists:materials,id',
            'loading_id' => 'required|exists:loadings,id',
            'place_id' => 'required|exists:places,id',
            'party_id' => 'required|exists:parties,id',
            'royalty_id' => 'nullable|exists:royalties,id',
            'royalty_number' => 'required_with:royalty_id',
            'royalty_tone' => 'required_with:royalty_id',
            'driver_id' => 'required|exists:drivers,id',
            'carting_id' => 'required',
            'note' => 'required',
        ]);

        if ($validated['gross_weight'] <= $validated['tare_weight']) {
            return redirect()->back()
                ->withErrors(['gross_weight' => 'Gross weight must be greater than tare weight'])
                ->withInput();
        }

        $validated['status'] = '2'; // Set status to 2 for completed sales that need audit
        Sales::findOrFail($id)->update($validated);

        // Save ID in session for PDF auto-download
        session([
            'pdf_sales_id' => $id,
            'auto_download_pdf' => true
        ]);

        return redirect()->route('sales.salesAudit')
            ->with('success', 'Sales updated successfully');
    }


    public function salesPdf($id)
    {
        $sales = Sales::findOrFail($id);

        $pdfData = [
            'challan_number' => $sales->id,
            'date_time' => $sales->date_time,
            'party' => $sales->party->name,
            'royalty' => $sales->royalty?->name,
            'royalty_number'=> $sales->royalty_number,
            'vehicle_number' => $sales->vehicle->name,
            'gross_weight' => $sales->gross_weight,
            'tare_weight' => $sales->tare_weight,
            'net_weight' => $sales->net_weight,
            'place' => $sales->place->name,
            'material' => $sales->material->name,
        ];

        $pdf = Pdf::loadView('sales.pdf', $pdfData);

        return $pdf->download("Sales.pdf");
    }

    public function salesAudit(Request $request)
    {
        $query = Sales::query();

        // Apply filters if provided
        if ($request->challan) {
            $query->where('id', 'like', '%' . $request->challan . '%');
        }

        if ($request->party) {
            $query->where('party_id', $request->party);
        }

        if ($request->material) {
            $query->where('material_id', $request->material);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get sales with status = 2 (completed after pending load)
        $sales = $query->with(['party', 'material', 'vehicle'])
            ->where('status', 2)
            ->latest()
            ->paginate(10);

        $allDates = Sales::select('created_at')
            ->distinct()
            ->get()
            ->map(fn($d) => $d->created_at->format('Y-m-d'));

        // Get all unique values for filters
        $allChallans = Sales::where('status', 2)->select('id')->distinct()->pluck('id');
        $allParties = Party::whereIn('id', Sales::where('status', 2)->distinct()->pluck('party_id'))->get();
        $allMaterials = Materials::whereIn('id', Sales::where('status', 2)->distinct()->pluck('material_id'))->get();

        if ($request->ajax()) {
            return view('sales.sales-audit', compact('sales', 'allDates', 'allChallans', 'allParties', 'allMaterials'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage());
        }

        return view('sales.sales-audit', compact('sales', 'allDates', 'allChallans', 'allParties', 'allMaterials'));
    }

    public function searchChallans(Request $request)
    {
        $module = $request->module;
        $searchType = $request->searchType;
        $searchData = $request->searchData;

        if ($module === 'sales') {
            $query = Sales::query();
        } else {
            $query = Purchase::query();
        }

        // Apply filters based on search type
        if ($searchType === 'challan' && !empty($searchData['challan'])) {
            $query->where('id', 'like', '%' . $searchData['challan'] . '%');
        } elseif ($searchType === 'transporter' && !empty($searchData['transporter'])) {
            $query->where('transporter', 'like', '%' . $searchData['transporter'] . '%');
        } elseif ($searchType === 'vehicle' && !empty($searchData['vehicle'])) {
            // For vehicle search, we need to join with vehicles table
            $vehicleIds = Vehicle::where('name', 'like', '%' . $searchData['vehicle'] . '%')->pluck('id');
            $query->whereIn('vehicle_id', $vehicleIds);
        } elseif ($searchType === 'date' && !empty($searchData['date'])) {
            $query->whereDate('created_at', $searchData['date']);
        } elseif ($searchType === 'date_range' && !empty($searchData['date_from']) && !empty($searchData['date_to'])) {
            $query->whereBetween('created_at', [
                $searchData['date_from'] . ' 00:00:00',
                $searchData['date_to'] . ' 23:59:59'
            ]);
        }

        $results = $query->with('vehicle')->latest()->limit(20)->get();

        // Return view with results
        if ($module === 'sales') {
            return view('sales.search-results', compact('results', 'module'))->render();
        } else {
            return view('purchase.search-results', compact('results', 'module'))->render();
        }
    }

    public function updatePartyWeight(Request $request, $id)
    {
        $request->validate([
            'party_weight' => 'required|numeric|min:0',
        ]);

        $sale = Sales::findOrFail($id);
        
        // If party_weight is provided and not empty, use it
        // Otherwise, it means we're using the net weight from the frontend
        if ($request->filled('party_weight')) {
            $sale->party_weight = $request->party_weight;
        } else {
            // Use net weight if party weight is not provided
            $sale->party_weight = $sale->net_weight;
        }
        
        $sale->status = 1; // Change status from 2 to 1 after party weight is filled
        $sale->save();

        return response()->json(['success' => true, 'message' => 'Party weight updated successfully']);
    }
}