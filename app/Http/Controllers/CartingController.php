<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Vehicle;
use App\Models\Materials;
use App\Models\Places;
use App\Models\Party;

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