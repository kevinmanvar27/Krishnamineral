<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Vehicle;
use App\Models\Materials;
use App\Models\Places;
use App\Models\Party;
use App\Models\Loading;
use Barryvdh\DomPDF\Facade\Pdf;

class CartingController extends Controller
{
    public function index(Request $request)
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

        if ($request->transporter) {
            // Filter by transporter name (vehicle_name)
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('vehicle_name', 'like', '%' . $request->transporter . '%');
            });
        }

        if ($request->place) {
            $query->where('place_id', $request->place);
        }

        if ($request->party) {
            $query->where('party_id', $request->party);
        }

        if ($request->carting_rate) {
            $query->where('carting_rate', $request->carting_rate);
        }

        if ($request->has('carting_radio') && $request->carting_radio !== null && $request->carting_radio !== '') {
            $query->where('carting_radio', $request->carting_radio);
        }

        // Handle single date filter
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }
        // Handle date range filters
        elseif ($request->date_from && $request->date_to) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        } elseif ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        } elseif ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Always filter for carting_id = 1 (carting records only)
        $query->where('carting_id', 0);

        // Get all sales with their relationships where status = 1
        $sales = $query->with(['party', 'material', 'vehicle', 'place'])
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
        $allPlaces = Places::whereIn('id', Sales::where('status', 1)->where('carting_id', 0)->distinct()->pluck('place_id'))->get();
        $allParties = Party::whereIn('id', Sales::where('status', 1)->where('carting_id', 0)->distinct()->pluck('party_id'))->get();
        // Get all unique transporter names (vehicle_name)
        $allTransporters = Vehicle::whereIn('id', Sales::where('status', 1)->where('carting_id', 0)->distinct()->pluck('vehicle_id'))
            ->select('vehicle_name')
            ->distinct()
            ->pluck('vehicle_name');

        if ($request->ajax()) {
            return view('carting.index', compact('sales', 'allChallans', 'allVehicles', 'allNetWeights', 'allPlaces', 'allParties', 'allTransporters', 'allDates'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage());
        }

        return view('carting.index', compact('sales', 'allChallans', 'allVehicles', 'allNetWeights', 'allPlaces', 'allParties', 'allTransporters', 'allDates'));
    }

    public function cartingStatement(Request $request)
    {
        $query = Sales::query();

        // Apply filters if provided
        if ($request->transporter_name) {
            // Filter by transporter name (vehicle_name)
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('vehicle_name', 'like', '%' . $request->transporter_name . '%');
            });
        }
        
        if ($request->vehicle_number) {
            // Filter by vehicle number (name)
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->vehicle_number . '%');
            });
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

        // Get all sales with their relationships where status = 1 and carting_id = 0 (carting records)
        $sales = $query->with(['party', 'material', 'vehicle', 'place', 'royalty', 'loading'])
            // ->where('status', 1)
            ->where('carting_id', 0)
            ->latest()
            ->paginate(10);

        // Group sales by transporter for transporter-wise table
        $transporterWiseSales = [];
        foreach ($sales as $sale) {
            // Calculate carting_amount: carting_rate * (party_weight if exists, otherwise net_weight)
            $weight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
            $newWeight = $weight / 1000;
            $cartingAmount = (!is_null($sale->carting_rate) && $newWeight > 0) ? $sale->carting_rate * $newWeight : 0;
            
            // Update the sale model with the calculated carting_amount
            $sale->carting_amount = $cartingAmount;
            
            // Get transporter name from vehicle
            $transporterName = $sale->vehicle->vehicle_name ?? 'N/A';
            
            if (!isset($transporterWiseSales[$transporterName])) {
                $transporterWiseSales[$transporterName] = [
                    'transporterName' => $transporterName,
                    'sales' => [],
                    'challanWiseData' => [], // For challan-wise totals
                    'netWeightTotal' => 0,
                    'partyWeightTotal' => 0,
                    'amountTotal' => 0,
                    'cartingAmountTotal' => 0
                ];
            }
            
            // Add sale to transporter data
            $transporterWiseSales[$transporterName]['sales'][] = $sale;
            
            // Initialize challan-wise data if not exists
            $challanId = $sale->id;
            if (!isset($transporterWiseSales[$transporterName]['challanWiseData'][$challanId])) {
                $transporterWiseSales[$transporterName]['challanWiseData'][$challanId] = [
                    'challanNumber' => 'S_' . $sale->id,
                    'netWeight' => 0,
                    'partyWeight' => 0,
                    'amount' => 0,
                    'cartingAmount' => 0,
                    'records' => []
                ];
            }
            
            // Add sale to challan data
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['records'][] = $sale;
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['netWeight'] += $sale->net_weight ?? 0;
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['partyWeight'] += $sale->party_weight ?? 0;
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['amount'] += $sale->amount ?? 0;
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['cartingAmount'] += $cartingAmount;
            
            // Update transporter totals
            $transporterWiseSales[$transporterName]['netWeightTotal'] += $sale->net_weight ?? 0;
            $transporterWiseSales[$transporterName]['partyWeightTotal'] += $sale->party_weight ?? 0;
            $transporterWiseSales[$transporterName]['amountTotal'] += $sale->amount ?? 0;
            $transporterWiseSales[$transporterName]['cartingAmountTotal'] += $cartingAmount;
        }

        // Calculate grand totals
        $grandTotalNetWeight = $sales->sum('net_weight');
        $grandTotalPartyWeight = $sales->sum('party_weight');
        $grandTotalAmount = $sales->sum('amount');
        
        // Calculate grand total carting amount
        $grandTotalCartingAmount = 0;
        foreach ($sales as $sale) {
            $grandTotalCartingAmount += $sale->carting_amount ?? 0;
        }

        // Calculate grand total display weight (party weight when available, otherwise net weight)
        $grandTotalDisplayWeight = 0;
        foreach ($sales as $sale) {
            $displayWeight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
            $grandTotalDisplayWeight += $displayWeight;
        }

        // Get all unique values for filters
        $allTransporters = Vehicle::whereIn('id', Sales::where('carting_id', 0)->distinct()->pluck('vehicle_id'))
            ->select('vehicle_name')
            ->distinct()
            ->pluck('vehicle_name');
            
        $allVehicles = Vehicle::whereIn('id', Sales::where('carting_id', 0)->distinct()->pluck('vehicle_id'))
            ->select('name')
            ->distinct()
            ->pluck('name');

        // Don't pass filter values to the view since we're handling this in JavaScript
        // This makes it consistent with other modules
        $filterValues = [];

        if ($request->ajax()) {
            return view('carting.statement', compact('sales', 'transporterWiseSales', 'allTransporters', 'allVehicles', 'grandTotalNetWeight', 'grandTotalPartyWeight', 'grandTotalAmount', 'grandTotalDisplayWeight', 'filterValues'))
                ->with('i', ($sales->currentPage() - 1) * $sales->perPage())
                ->render();
        }

        return view('carting.statement', compact('sales', 'transporterWiseSales', 'allTransporters', 'allVehicles', 'grandTotalNetWeight', 'grandTotalPartyWeight', 'grandTotalAmount', 'grandTotalDisplayWeight', 'filterValues'));
    }

    public function printCartingStatement(Request $request)
    {
        $query = Sales::query();

        // Apply filters if provided
        if ($request->transporter_name) {
            // Filter by transporter name (vehicle_name)
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('vehicle_name', 'like', '%' . $request->transporter_name . '%');
            });
        }
        
        if ($request->vehicle_number) {
            // Filter by vehicle number (name)
            $query->whereHas('vehicle', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->vehicle_number . '%');
            });
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

        // Get all sales with their relationships where status = 1 and carting_id = 0 (carting records)
        $sales = $query->with(['party', 'material', 'vehicle', 'place', 'royalty', 'loading'])
            // ->where('status', 1)
            ->where('carting_id', 0)
            ->latest()
            ->get(); // Get all records without pagination for printing

        // Group sales by transporter for transporter-wise table
        $transporterWiseSales = [];
        foreach ($sales as $sale) {
            // Calculate carting_amount: carting_rate * (party_weight if exists, otherwise net_weight)
            $weight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
            $newWeight = $weight / 1000;
            $cartingAmount = (!is_null($sale->carting_rate) && $newWeight > 0) ? $sale->carting_rate * $newWeight : 0;
            
            // Update the sale model with the calculated carting_amount
            $sale->carting_amount = $cartingAmount;
            
            // Get transporter name from vehicle
            $transporterName = $sale->vehicle->vehicle_name ?? 'N/A';
            
            if (!isset($transporterWiseSales[$transporterName])) {
                $transporterWiseSales[$transporterName] = [
                    'transporterName' => $transporterName,
                    'sales' => [],
                    'challanWiseData' => [], // For challan-wise totals
                    'netWeightTotal' => 0,
                    'partyWeightTotal' => 0,
                    'amountTotal' => 0,
                    'cartingAmountTotal' => 0
                ];
            }
            
            // Add sale to transporter data
            $transporterWiseSales[$transporterName]['sales'][] = $sale;
            
            // Initialize challan-wise data if not exists
            $challanId = $sale->id;
            if (!isset($transporterWiseSales[$transporterName]['challanWiseData'][$challanId])) {
                $transporterWiseSales[$transporterName]['challanWiseData'][$challanId] = [
                    'challanNumber' => 'S_' . $sale->id,
                    'netWeight' => 0,
                    'partyWeight' => 0,
                    'amount' => 0,
                    'records' => []
                ];
            }
            
            // Add sale to challan data
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['records'][] = $sale;
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['netWeight'] += $sale->net_weight ?? 0;
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['partyWeight'] += $sale->party_weight ?? 0;
            $transporterWiseSales[$transporterName]['challanWiseData'][$challanId]['amount'] += $sale->amount ?? 0;
            
            // Update transporter totals
            $transporterWiseSales[$transporterName]['netWeightTotal'] += $sale->net_weight ?? 0;
            $transporterWiseSales[$transporterName]['partyWeightTotal'] += $sale->party_weight ?? 0;
            $transporterWiseSales[$transporterName]['amountTotal'] += $sale->amount ?? 0;
        }

        // If printing a specific challan, use the challan PDF view
        if ($request->challan_id && isset($transporterWiseSales) && count($transporterWiseSales) > 0) {
            // Get the first transporter (there should only be one when filtering by challan_id)
            $transporterData = reset($transporterWiseSales);
            $challanData = $transporterData['challanWiseData'][$request->challan_id] ?? null;
            
            if ($challanData) {
                // Load the challan PDF view
                $pdf = Pdf::loadView('sales.challan-pdf', compact('challanData', 'transporterData'));
                
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
        
        // Calculate grand total carting amount
        $grandTotalCartingAmount = 0;
        foreach ($sales as $sale) {
            $grandTotalCartingAmount += $sale->carting_amount ?? 0;
        }

        // Calculate grand total display weight (party weight when available, otherwise net weight)
        $grandTotalDisplayWeight = 0;
        foreach ($sales as $sale) {
            $displayWeight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
            $grandTotalDisplayWeight += $displayWeight;
        }

        // Prepare filter values for the view
        $filterValues = [
            'transporter_name' => $request->transporter_name ?? '',
            'vehicle_number' => $request->vehicle_number ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? ''
        ];

        // Load the PDF view with the same data
        $pdf = Pdf::loadView('carting.statement-pdf', compact('sales', 'transporterWiseSales', 'grandTotalNetWeight', 'grandTotalPartyWeight', 'grandTotalAmount', 'grandTotalDisplayWeight', 'grandTotalCartingAmount', 'filterValues'));
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'landscape');
        
        // Return the PDF as a download
        return $pdf->download('Carting_Statement.pdf');
    }

    public function updateCarting(Request $request, $id)
    {
        $request->validate([
            'carting_rate' => 'nullable|numeric',
            'carting_radio' => 'nullable|in:0,1',
        ]);

        $sale = Sales::findOrFail($id);
        
        if ($request->filled('carting_rate')) {
            $sale->carting_rate = $request->carting_rate;
        }
        
        if ($request->filled('carting_radio')) {
            $sale->carting_radio = $request->carting_radio;
        }
        
        $sale->save();

        return response()->json(['success' => true, 'message' => 'Carting record updated successfully']);
    }

    public function bulkUpdateCarting(Request $request)
    {
        $request->validate([
            'sales' => 'required|array',
            'sales.*.id' => 'required|exists:sales,id',
            'sales.*.carting_rate' => 'nullable|numeric',
            'sales.*.carting_radio' => 'nullable|in:0,1',
        ]);

        foreach ($request->sales as $saleData) {
            $sale = Sales::findOrFail($saleData['id']);
            
            if (!is_null($saleData['carting_rate'])) {
                $sale->carting_rate = $saleData['carting_rate'];
            }
            
            if (!is_null($saleData['carting_radio'])) {
                $sale->carting_radio = $saleData['carting_radio'];
            }
            
            $sale->save();
        }

        return response()->json(['success' => true, 'message' => 'Carting records updated successfully']);
    }
}