<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
use App\Models\Blasting;
use App\Models\Drilling;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Concerns\DriverHelper;

class SalesController extends Controller
{
    use DriverHelper;
    
    public function index(Request $request)
    {
        $query = Sales::query();

        // Add challan (ID) filter
        if ($request->challan) {
            $query->where('id', 'like', '%' . $request->challan . '%');
        }

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
        $allChallans = Sales::select('id')->distinct()->pluck('id');

        if ($request->ajax()) {
            return view('sales.index', compact('sales', 'allTransporters', 'allDates', 'allContacts', 'allChallans'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage());
        }

        return view('sales.index', compact('sales', 'allTransporters', 'allDates', 'allContacts', 'allChallans'));    
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
        // Get all vehicles for sales
        $vehicles = Vehicle::where('table_type', 'sales')->get();
        
        // Get vehicle IDs that have pending loads (status = 0)
        $pendingVehicleIds = Sales::where('status', 0)->pluck('vehicle_id')->unique();
        
        return view('sales.create-sales', compact('sales', 'vehicles', 'pendingVehicleIds'));    
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'date_time' => 'required',
            'vehicle_id' => 'required',
            'transporter' => 'required',
            'tare_weight' => 'required',
            'contact_number' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'driver_contact_number' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'driver_id' => 'nullable',
        ]);
        
        // Process the driver_id to extract the original ID if it's a combined ID
        if (isset($validated['driver_id'])) {
            $driverId = $validated['driver_id'];
            
            // If it's a user driver, create or get the corresponding driver entry
            if (strpos($driverId, 'user_') === 0) {
                $userId = str_replace('user_', '', $driverId);
                $user = \App\Models\User::find($userId);
                
                if ($user) {
                    // Check if a driver entry already exists for this user
                    $existingDriver = \App\Models\Driver::where('user_id', $userId)->first();
                    
                    if ($existingDriver) {
                        // Use existing driver entry
                        $validated['driver_id'] = $existingDriver->id;
                    } else {
                        // Create a new driver entry for this user
                        $driverEntry = \App\Models\Driver::create([
                            'name' => $user->name,
                            'driver' => 'Krishna Employee',
                            'contact_number' => $user->contact_number ?? '',
                            'table_type' => 'sales',
                            'user_id' => $userId
                        ]);
                        
                        $validated['driver_id'] = $driverEntry->id;
                    }
                }
            }
            // If it's a regular driver, extract the ID
            elseif (strpos($driverId, 'driver_') === 0) {
                $originalId = str_replace('driver_', '', $driverId);
                $validated['driver_id'] = $originalId;
            }
        }
        
        Sales::create($validated);
        return redirect()->route(Auth::user()->can('pending-load-sales') ? 'sales.pendingLoad' : 'home')
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
        $drivers  = $this->getCombinedDrivers('sales');
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
            'driver_id' => 'required',
            'carting_id' => 'required',
            'rate' => 'nullable|numeric',
            'gst' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            'note' => 'nullable',
        ]);

        if ($validated['gross_weight'] <= $validated['tare_weight']) {
            return redirect()->back()
                ->withErrors(['gross_weight' => 'Gross weight must be greater than tare weight'])
                ->withInput();
        }

        $validated['status'] = '2'; // Set status to 2 for completed sales that need audit
        
        // Process the driver_id to extract the original ID if it's a combined ID
        if (isset($validated['driver_id'])) {
            $driverId = $validated['driver_id'];
            
            // If it's a user driver, create or get the corresponding driver entry
            if (strpos($driverId, 'user_') === 0) {
                $userId = str_replace('user_', '', $driverId);
                $user = \App\Models\User::find($userId);
                
                if ($user) {
                    // Check if a driver entry already exists for this user
                    $existingDriver = \App\Models\Driver::where('user_id', $userId)->first();
                    
                    if ($existingDriver) {
                        // Use existing driver entry
                        $validated['driver_id'] = $existingDriver->id;
                    } else {
                        // Create a new driver entry for this user
                        $driverEntry = \App\Models\Driver::create([
                            'name' => $user->name,
                            'driver' => 'Krishna Employee',
                            'contact_number' => $user->contact_number ?? '',
                            'table_type' => 'sales',
                            'user_id' => $userId
                        ]);
                        
                        $validated['driver_id'] = $driverEntry->id;
                    }
                }
            }
            // If it's a regular driver, extract the ID
            elseif (strpos($driverId, 'driver_') === 0) {
                $originalId = str_replace('driver_', '', $driverId);
                $validated['driver_id'] = $originalId;
            }
        }
        
        Sales::findOrFail($id)->update($validated);

        // Save ID in session for PDF auto-download
        session([
            'pdf_sales_id' => $id,
            'auto_download_pdf' => true
        ]);

        return redirect()->route(Auth::user()->can('audit-sales') ? 'sales.salesAudit' : 'home')
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

    public function salesStatement(Request $request)
    {
        $query = Sales::query();

        // Apply filters if provided
        if ($request->party_name) {
            $party = Party::where('name', $request->party_name)->first();
            if ($party) {
                $query->where('party_id', $party->id);
            }
            // If party not found, don't add any filter (show no results)
        }
        if ($request->material_name) {
            $query->where('material_id', $request->material_name);
        }

        // Handle date filtering properly
        if ($request->date_from && $request->date_to) {
            // Both dates provided - filter between dates (inclusive)
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            // Only from date provided
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->date_to) {
            // Only to date provided
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get all sales with their relationships where status = 1 (completed sales)
        $sales = $query->with(['party', 'material', 'vehicle', 'place', 'royalty'])
            // ->where('status', 1)
            ->latest()
            ->paginate(10);

        // Group sales by party for party-wise table
        $partyWiseSales = [];
        foreach ($sales as $sale) {
            $partyId = $sale->party_id;
            if (!isset($partyWiseSales[$partyId])) {
                $partyWiseSales[$partyId] = [
                    'party' => $sale->party,
                    'sales' => [],
                    'challanWiseData' => [], // For challan-wise totals
                    'netWeightTotal' => 0,
                    'partyWeightTotal' => 0,
                    'amountTotal' => 0
                ];
            }
            
            // Add sale to party data
            $partyWiseSales[$partyId]['sales'][] = $sale;
            
            // Initialize challan-wise data if not exists
            $challanId = $sale->id;
            if (!isset($partyWiseSales[$partyId]['challanWiseData'][$challanId])) {
                $partyWiseSales[$partyId]['challanWiseData'][$challanId] = [
                    'challanNumber' => 'S_' . $sale->id,
                    'netWeight' => 0,
                    'partyWeight' => 0,
                    'amount' => 0,
                    'records' => []
                ];
            }
            
            // Add sale to challan data
            $partyWiseSales[$partyId]['challanWiseData'][$challanId]['records'][] = $sale;
            $partyWiseSales[$partyId]['challanWiseData'][$challanId]['netWeight'] += $sale->net_weight ?? 0;
            $partyWiseSales[$partyId]['challanWiseData'][$challanId]['partyWeight'] += $sale->party_weight ?? 0;
            $partyWiseSales[$partyId]['challanWiseData'][$challanId]['amount'] += $sale->amount ?? 0;
            
            // Update party totals
            $partyWiseSales[$partyId]['netWeightTotal'] += $sale->net_weight ?? 0;
            $partyWiseSales[$partyId]['partyWeightTotal'] += $sale->party_weight ?? 0;
            $partyWiseSales[$partyId]['amountTotal'] += $sale->amount ?? 0;
        }

        // Calculate grand totals
        $grandTotalNetWeight = $sales->sum('net_weight');
        $grandTotalPartyWeight = $sales->sum('party_weight');
        $grandTotalAmount = $sales->sum('amount');

        // Calculate grand total display weight (party weight when available, otherwise net weight)
        $grandTotalDisplayWeight = 0;
        foreach ($sales as $sale) {
            $displayWeight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
            $grandTotalDisplayWeight += $displayWeight;
        }

        // Get all unique values for filters
        $allParties = Party::whereIn('id', Sales::distinct()->pluck('party_id'))->get();
        $allMaterials = Materials::whereIn('id', Sales::distinct()->pluck('material_id'))->get();

        // Don't pass filter values to the view since we're handling this in JavaScript
        // This makes it consistent with other modules
        $filterValues = [];

        if ($request->ajax()) {
            return view('sales.statement', compact('sales', 'partyWiseSales', 'allParties', 'allMaterials', 'grandTotalNetWeight', 'grandTotalPartyWeight', 'grandTotalAmount', 'grandTotalDisplayWeight', 'filterValues'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage())
                ->render();
        }

        return view('sales.statement', compact('sales', 'partyWiseSales', 'allParties', 'allMaterials', 'grandTotalNetWeight', 'grandTotalPartyWeight', 'grandTotalAmount', 'grandTotalDisplayWeight', 'filterValues'));
    }

    public function printStatement(Request $request)
    {
        $query = Sales::query();

        // Apply filters if provided
        if ($request->party_name) {
            $party = Party::where('name', $request->party_name)->first();
            if ($party) {
                $query->where('party_id', $party->id);
            } else {
                $query->where('party_id', 0); // No results if party not found
            }
        }
        
        // Material filter for print statement
        if ($request->material_name) {
            $query->where('material_id', $request->material_name);
        }

        // Handle date filtering properly
        if ($request->date_from && $request->date_to) {
            // Both dates provided - filter between dates (inclusive)
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            // Only from date provided
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->date_to) {
            // Only to date provided
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // If a specific challan is requested, filter by that challan
        if ($request->challan_id) {
            $query->where('id', $request->challan_id);
        }

        // Get all sales with their relationships where status = 1 (completed sales)
        $sales = $query->with(['party', 'material', 'vehicle', 'place', 'royalty'])
            // ->where('status', 1)
            ->latest()
            ->get(); // Get all records without pagination for printing

        // Group sales by party for party-wise table
        $partyWiseSales = [];
        foreach ($sales as $sale) {
            $partyId = $sale->party_id;
            if (!isset($partyWiseSales[$partyId])) {
                $partyWiseSales[$partyId] = [
                    'party' => $sale->party,
                    'sales' => [],
                    'challanWiseData' => [], // For challan-wise totals
                    'netWeightTotal' => 0,
                    'partyWeightTotal' => 0,
                    'amountTotal' => 0
                ];
            }
            
            // Add sale to party data
            $partyWiseSales[$partyId]['sales'][] = $sale;
            
            // Initialize challan-wise data if not exists
            $challanId = $sale->id;
            if (!isset($partyWiseSales[$partyId]['challanWiseData'][$challanId])) {
                $partyWiseSales[$partyId]['challanWiseData'][$challanId] = [
                    'challanNumber' => 'S_' . $sale->id,
                    'netWeight' => 0,
                    'partyWeight' => 0,
                    'amount' => 0,
                    'records' => []
                ];
            }
            
            // Add sale to challan data
            $partyWiseSales[$partyId]['challanWiseData'][$challanId]['records'][] = $sale;
            $partyWiseSales[$partyId]['challanWiseData'][$challanId]['netWeight'] += $sale->net_weight ?? 0;
            $partyWiseSales[$partyId]['challanWiseData'][$challanId]['partyWeight'] += $sale->party_weight ?? 0;
            $partyWiseSales[$partyId]['challanWiseData'][$challanId]['amount'] += $sale->amount ?? 0;
            
            // Update party totals
            $partyWiseSales[$partyId]['netWeightTotal'] += $sale->net_weight ?? 0;
            $partyWiseSales[$partyId]['partyWeightTotal'] += $sale->party_weight ?? 0;
            $partyWiseSales[$partyId]['amountTotal'] += $sale->amount ?? 0;
        }

        // If printing a specific challan, use the challan PDF view
        if ($request->challan_id && isset($partyWiseSales) && count($partyWiseSales) > 0) {
            // Get the first party (there should only be one when filtering by challan_id)
            $partyData = reset($partyWiseSales);
            $challanData = $partyData['challanWiseData'][$request->challan_id] ?? null;
            
            if ($challanData) {
                // Load the challan PDF view
                $pdf = Pdf::loadView('sales.challan-pdf', compact('challanData', 'partyData'));
                
                // Set paper size and orientation
                $pdf->setPaper('A4', 'portrait');
                
                // Return the PDF as a download
                return $pdf->download('Challan_' . $challanData['challanNumber'] . '.pdf');
            }
        }

        // Calculate grand totals
        $grandTotalNetWeight = $sales->sum('net_weight');
        $grandTotalPartyWeight = $sales->sum('party_weight');
        $grandTotalAmount = $sales->sum('amount');

        // Calculate grand total display weight (party weight when available, otherwise net weight)
        $grandTotalDisplayWeight = 0;
        foreach ($sales as $sale) {
            $displayWeight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
            $grandTotalDisplayWeight += $displayWeight;
        }

        // Prepare filter values for the view
        $filterValues = [
            'party_name' => $request->party_name ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? ''
        ];

        // Load the PDF view with the same data
        $pdf = Pdf::loadView('sales.statement-pdf', compact('sales', 'partyWiseSales', 'grandTotalNetWeight', 'grandTotalPartyWeight', 'grandTotalAmount', 'grandTotalDisplayWeight', 'filterValues'));
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');
        
        // Return the PDF as a download
        return $pdf->download('Sales_Statement.pdf');
    }

    public function searchChallans(Request $request)
    {
        try {
            $module = $request->module;
            $searchType = $request->searchType;
            $searchData = $request->searchData;

            if ($module === 'sales') {
                $query = Sales::query();
            } elseif ($module === 'purchase') {
                $query = Purchase::query();
            } elseif ($module === 'blasting') {
                $query = Blasting::query();
            } elseif ($module === 'drilling') {
                $query = Drilling::query();
            } else {
                // Return error if module is not supported
                return response()->json(['error' => 'Invalid module selected'], 400);
            }

            // Apply filters based on search type
            if ($searchType === 'challan' && !empty($searchData['challan'])) {
                if ($module === 'blasting') {
                    // For blasting, we search by blasting_id
                    $query->where('blasting_id', 'like', '%' . $searchData['challan'] . '%');
                } elseif ($module === 'drilling') {
                    // For drilling, we search by drilling_id
                    $query->where('drilling_id', 'like', '%' . $searchData['challan'] . '%');
                } else {
                    $query->where('id', 'like', '%' . $searchData['challan'] . '%');
                }
            } elseif ($searchType === 'transporter' && !empty($searchData['transporter'])) {
                if ($module === 'blasting') {
                    // For blasting, we can search by blaster name
                    $query->whereHas('blasterName', function ($q) use ($searchData) {
                        $q->where('b_name', 'like', '%' . $searchData['transporter'] . '%');
                    });
                } elseif ($module === 'drilling') {
                    // For drilling, we can search by drilling name
                    $query->whereHas('drillingName', function ($q) use ($searchData) {
                        $q->where('d_name', 'like', '%' . $searchData['transporter'] . '%');
                    });
                } else {
                    $query->where('transporter', 'like', '%' . $searchData['transporter'] . '%');
                }
            } elseif ($searchType === 'vehicle' && !empty($searchData['vehicle'])) {
                if ($module === 'sales' || $module === 'purchase') {
                    // For vehicle search, we need to join with vehicles table
                    $vehicleIds = Vehicle::where('name', 'like', '%' . $searchData['vehicle'] . '%')->pluck('id');
                    $query->whereIn('vehicle_id', $vehicleIds);
                }
                // For blasting and drilling, there's no vehicle field, so we skip this filter
            } elseif ($searchType === 'date' && !empty($searchData['date'])) {
                if ($module === 'blasting' || $module === 'drilling') {
                    $query->whereDate('date_time', $searchData['date']);
                } else {
                    $query->whereDate('created_at', $searchData['date']);
                }
            } elseif ($searchType === 'date_range' && !empty($searchData['date_from']) && !empty($searchData['date_to'])) {
                if ($module === 'blasting' || $module === 'drilling') {
                    $query->whereBetween('date_time', [
                        $searchData['date_from'] . ' 00:00:00',
                        $searchData['date_to'] . ' 23:59:59'
                    ]);
                } else {
                    $query->whereBetween('created_at', [
                        $searchData['date_from'] . ' 00:00:00',
                        $searchData['date_to'] . ' 23:59:59'
                    ]);
                }
            }

            // Load appropriate relationships based on module
            if ($module === 'sales' || $module === 'purchase') {
                $results = $query->with('vehicle')->latest()->limit(20)->get();
            } elseif ($module === 'blasting') {
                $results = $query->with('blasterName')->latest()->limit(20)->get();
            } elseif ($module === 'drilling') {
                $results = $query->with('drillingName')->latest()->limit(20)->get();
            } else {
                $results = $query->latest()->limit(20)->get();
            }

            // Return view with results
            if ($module === 'sales') {
                return view('sales.search-results', compact('results', 'module'))->render();
            } elseif ($module === 'purchase') {
                return view('purchase.search-results', compact('results', 'module'))->render();
            } elseif ($module === 'blasting') {
                return view('blasting.search-results', compact('results', 'module'))->render();
            } elseif ($module === 'drilling') {
                return view('drilling.search-results', compact('results', 'module'))->render();
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Search error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while searching: ' . $e->getMessage()], 500);
        }
    }

    public function updatePartyWeight(Request $request, $id)
    {
        $request->validate([
            'party_weight' => 'nullable|numeric|min:0',
        ]);

        $sale = Sales::findOrFail($id);
        
        // If party_weight is provided and not empty, and not zero, use it
        // Otherwise, it means we're using the net weight from the frontend
        if ($request->filled('party_weight') && $request->party_weight != 0) {
            $sale->party_weight = $request->party_weight;
        } else {
            // Use net weight if party weight is not provided or is zero
            $sale->party_weight = $sale->net_weight;
        }
        
        $sale->status = 1; // Change status from 2 to 1 after party weight is filled
        $sale->save();

        return response()->json(['success' => true, 'message' => 'Party weight updated successfully']);
    }

    public function updateRate(Request $request, $id)
    {
        $request->validate([
            'rate' => 'nullable|numeric|min:0',
            'gst' => 'nullable|numeric|min:0',
        ]);

        $sale = Sales::findOrFail($id);
        
        if ($request->filled('rate')) {
            $sale->rate = $request->rate;
        }
        
        if ($request->filled('gst')) {
            $sale->gst = $request->gst;
        }
        
        // Calculate amount using party_weight if available and not zero, otherwise net_weight
        $weight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : $sale->net_weight;
        $amount = 0;
        
        $newWeight = $weight / 1000;
        if (!is_null($sale->rate) && !is_null($newWeight)) {
            $amount = $sale->rate * $newWeight;
            if (!is_null($sale->gst)) {
                $amount += ($amount * $sale->gst / 100);
            }
            $sale->amount = $amount;
        }
        
        $sale->save();

        return response()->json([
            'success' => true, 
            'message' => 'Sales record updated successfully',
            'amount' => $amount
        ]);
    }

    public function bulkUpdateRate(Request $request)
    {
        $request->validate([
            'sales' => 'required|array',
            'sales.*.id' => 'required|exists:sales,id',
            'sales.*.rate' => 'nullable|numeric|min:0',
            'sales.*.gst' => 'nullable|numeric|min:0',
        ]);

        foreach ($request->sales as $saleData) {
            $sale = Sales::findOrFail($saleData['id']);
            
            if (!is_null($saleData['rate'])) {
                $sale->rate = $saleData['rate'];
            }
            
            if (!is_null($saleData['gst'])) {
                $sale->gst = $saleData['gst'];
            }
            
            // Calculate amount using party_weight if available and not zero, otherwise net_weight
            $weight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : $sale->net_weight;
            $amount = 0;
            
            $newWeight = $weight / 1000;
            if (!is_null($sale->rate) && !is_null($newWeight)) {
                $amount = $sale->rate * $newWeight;
                if (!is_null($sale->gst)) {
                    $amount += ($amount * $sale->gst / 100);
                }
                $sale->amount = $amount;
            }
            
            $sale->save();
        }

        return response()->json(['success' => true, 'message' => 'Sales records updated successfully']);
    }

    public function rate(Request $request)
    {
        $query = Sales::query();

        // Apply filters if provided
        if ($request->challan) {
            $query->where('id', 'like', '%' . $request->challan . '%');
        }

        if ($request->vehicle) {
            $query->where('vehicle_id', $request->vehicle);
        }

        if ($request->net_weight) {
            $query->where('net_weight', $request->net_weight);
        }

        if ($request->party_weight) {
            $query->where('party_weight', $request->party_weight);
        }

        if ($request->material) {
            $query->where('material_id', $request->material);
        }

        if ($request->place) {
            $query->where('place_id', $request->place);
        }

        if ($request->party) {
            $query->where('party_id', $request->party);
        }

        if ($request->royalty) {
            $query->where('royalty_id', $request->royalty);
        }

        if ($request->rate) {
            $query->where('rate', $request->rate);
        }

        if ($request->gst) {
            $query->where('gst', $request->gst);
        }

        // Always filter for carting_id = 0 (carting records only)
        $query->where('carting_id', 0);

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

        // Get all sales with their relationships where status = 1
        $sales = $query->with(['party', 'material', 'vehicle', 'place', 'royalty'])
            ->where('status', 1)
            ->latest()
            ->paginate(10);

        $allDates = Sales::select('created_at')
            ->where('status', 1)
            ->where('carting_id', 0)
            ->distinct()
            ->get()
            ->map(fn($d) => $d->created_at->format('Y-m-d'));

        // Get all unique values for filters
        $allChallans = Sales::where('status', 1)->where('carting_id', 0)->select('id')->distinct()->pluck('id');
        $allVehicles = Vehicle::whereIn('id', Sales::where('status', 1)->where('carting_id', 0)->distinct()->pluck('vehicle_id'))->get();
        $allNetWeights = Sales::where('status', 1)->where('carting_id', 0)->select('net_weight')->distinct()->pluck('net_weight');
        $allPartyWeights = Sales::where('status', 1)->where('carting_id', 0)->select('party_weight')->distinct()->pluck('party_weight');
        $allMaterials = Materials::whereIn('id', Sales::where('status', 1)->where('carting_id', 0)->distinct()->pluck('material_id'))->get();
        $allPlaces = Places::whereIn('id', Sales::where('status', 1)->where('carting_id', 0)->distinct()->pluck('place_id'))->get();
        $allParties = Party::whereIn('id', Sales::where('status', 1)->where('carting_id', 0)->distinct()->pluck('party_id'))->get();
        $allRoyalties = Royalty::whereIn('id', Sales::where('status', 1)->where('carting_id', 0)->distinct()->pluck('royalty_id'))->get();

        if ($request->ajax()) {
            return view('sales.rate', compact('sales', 'allDates', 'allChallans', 'allVehicles', 'allNetWeights', 'allPartyWeights', 'allMaterials', 'allPlaces', 'allParties', 'allRoyalties'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage());
        }

        return view('sales.rate', compact('sales', 'allDates', 'allChallans', 'allVehicles', 'allNetWeights', 'allPartyWeights', 'allMaterials', 'allPlaces', 'allParties', 'allRoyalties'));
    }
}