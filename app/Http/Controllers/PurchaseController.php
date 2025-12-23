<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Materials;
use App\Models\Loading;
use App\Models\Places;
use App\Models\PurchaseReceiver;
use App\Models\Royalty;
use App\Models\Driver;
use App\Models\PurchaseQuarry;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Concerns\DriverHelper;

class PurchaseController extends Controller
{
    use DriverHelper;
    
    public function index(Request $request)
    {
        $query = Purchase::query();

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

        $purchases = $query->with('vehicle')
            ->where('status', 1)
            ->latest()
            ->paginate(5);

        $allDates = Purchase::select('created_at')
            ->distinct()
            ->get()
            ->map(fn($d) => $d->created_at->format('Y-m-d'));   
        $allTransporters = Purchase::where('status', 1)->select('transporter')->distinct()->pluck('transporter');
        $allContacts = Purchase::where('status', 1)->select('contact_number')->distinct()->pluck('contact_number');
        $allChallans = Purchase::where('status', 1)->select('id')->distinct()->pluck('id');
        $allVehicles = Vehicle::whereIn('id', Purchase::where('status', 1)->distinct()->pluck('vehicle_id'))->get();

        if ($request->ajax()) {
            return view('purchase.index', compact('purchases', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'))
                ->with('i', ($purchases->currentPage() - 1) * $purchases->perPage());
        }

        return view('purchase.index', compact('purchases', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'));    
    }

    public function editIndex(Request $request)
    {
        $query = Purchase::query();

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

        $purchases = $query->with('vehicle')
            ->where('status', 1)
            ->latest()
            ->paginate(5);

        $allDates = Purchase::select('created_at')
            ->distinct()
            ->get()
            ->map(fn($d) => $d->created_at->format('Y-m-d'));   
        $allTransporters = Purchase::where('status', 1)->select('transporter')->distinct()->pluck('transporter');
        $allContacts = Purchase::where('status', 1)->select('contact_number')->distinct()->pluck('contact_number');
        $allChallans = Purchase::where('status', 1)->select('id')->distinct()->pluck('id');
        $allVehicles = Vehicle::whereIn('id', Purchase::where('status', 1)->distinct()->pluck('vehicle_id'))->get();

        if ($request->ajax()) {
            return view('purchase.edit-index', compact('purchases', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'))
                ->with('i', ($purchases->currentPage() - 1) * $purchases->perPage());
        }
        return view('purchase.edit-index', compact('purchases', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        
    }

    public function pendingLoad(Request $request)
    {
        $query = Purchase::query();

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


        $purchases = $query->with('vehicle')
            ->where('status', 0)
            ->latest()
            ->paginate(5);

        $allDates = Purchase::select('created_at')
            ->distinct()
            ->get()
            ->map(fn($d) => $d->created_at->format('Y-m-d'));   
        $allTransporters = Purchase::where('status', 0)->select('transporter')->distinct()->pluck('transporter');
        $allContacts = Purchase::where('status', 0)->select('contact_number')->distinct()->pluck('contact_number');
        $allChallans = Purchase::where('status', 0)->select('id')->distinct()->pluck('id');
        $allVehicles = Vehicle::whereIn('id', Purchase::where('status', 0)->distinct()->pluck('vehicle_id'))->get();

        if ($request->ajax()) {
            return view('purchase.pendingLoad-purchase', compact('purchases', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'))
                ->with('i', ($purchases->currentPage() - 1) * $purchases->perPage());
        }

        return view('purchase.pendingLoad-purchase', compact('purchases', 'allTransporters', 'allDates', 'allContacts', 'allChallans', 'allVehicles'));
    }

    public function create(Purchase $purchases)
    {
        $latestPurchase = Purchase::latest('id')->first();
        $vehicles = Vehicle::where('table_type', 'purchase')->get();
        $materials = Materials::where('table_type', 'purchase')->get();
        $loadings = Loading::where('table_type', 'purchase')->get();
        $quarries = PurchaseQuarry::where('table_type', 'purchase')->get();
        $purchaseReceivers = PurchaseReceiver::where('table_type', 'purchase')->get();
        $royalties = Royalty::where('table_type', 'purchase')->get();
        $drivers  = $this->getCombinedDrivers('purchase');
        $employees = User::all();
        return view('purchase.create-purchase', compact('latestPurchase', 'vehicles', 'materials', 'loadings', 'quarries', 'purchaseReceivers', 'royalties', 'drivers', 'employees'));    
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'date_time' => 'required',
            'vehicle_id' => 'required',
            'driver_id' => 'required',
            'transporter' => 'required',
            'contact_number' => 'required|digits:10|regex:/^[0-9+\-\s]+$/u',
            'driver_contact_number' => 'required|digits:10|regex:/^[0-9+\-\s]+$/u',
            'tare_weight' => 'required',
        ]);

        // if ($validated['gross_weight'] <= $validated['tare_weight']) {
        //     return redirect()->back()
        //         ->withErrors(['gross_weight' => 'Gross weight must be greater than tare weight'])
        //         ->withInput();
        // }

        // Add default status of 0 (pending)
        $validated['status'] = 0;
        
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
                            'table_type' => 'purchase',
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
        
        $purchase = Purchase::create($validated);
        
        // Mark the driver as active when purchase is created
        if ($purchase && isset($validated['driver_id'])) {
            $driver = \App\Models\Driver::find($validated['driver_id']);
            
            if ($driver) {
                // Update the driver's activity
                $driver->update([
                    'is_active' => true,
                    'last_active_at' => now()
                ]);
            }
        }

        session([
            'pdf_purchase_id' => $purchase->id,
            'auto_download_pdf' => true,
        ]);

        return redirect()->route(Auth::user()->can('pending-load-purchase') ? 'purchase.pendingLoad' : 'home')
            ->with('success', 'Purchase created successfully.');
    }

    public function show($id)
    {
        $purchases = Purchase::find($id);
        return view('purchase.show', compact('purchases'));
    }

    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $vehicles = Vehicle::where('table_type', 'purchase')->get();
        $materials = Materials::where('table_type', 'purchase')->get();
        $loadings = Loading::where('table_type', 'purchase')->get();
        $quarries = PurchaseQuarry::where('table_type', 'purchase')->get();
        $purchaseReceivers = PurchaseReceiver::where('table_type', 'purchase')->get();
        $royalties = Royalty::where('table_type', 'purchase')->get();
        $drivers  = $this->getCombinedDrivers('purchase');
        $employees = User::all();
        return view('purchase.edit-purchase', compact('purchase', 'vehicles', 'materials', 'loadings', 'quarries', 'purchaseReceivers', 'royalties', 'drivers', 'employees'));    
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
            'quarry_id' => 'required|exists:purchase_quarries,id',
            'receiver_id' => 'required|exists:purchase_receivers,id',
            'driver_id' => 'required',
            'carting_id' => 'required',
            'note' => 'nullable',
        ]);

        if ($validated['gross_weight'] <= $validated['tare_weight']) {
            return redirect()->back()
                ->withErrors(['gross_weight' => 'Gross weight must be greater than tare weight'])
                ->withInput();
        }

        $validated['status'] = '1';
        
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
                            'table_type' => 'purchase',
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
        
        // Handle driver assignment for activity tracking
        if (isset($validated['driver_id'])) {
            $driver = \App\Models\Driver::find($validated['driver_id']);
            
            if ($driver) {
                // Update the driver's activity
                $driver->update([
                    'is_active' => true,
                    'last_active_at' => now()
                ]);
            }
        }
        
        Purchase::findOrFail($id)->update($validated);

        // Save ID in session for PDF auto-download
        session([
            'pdf_purchase_id' => $id,
            'auto_download_pdf' => true
        ]);

        return redirect()->route(Auth::user()->can('edit-purchase') ? 'purchase.editIndex' : 'home')
            ->with('success', 'Purchase updated successfully');
    }

    public function purchasePdf($id)
    {
        $purchase = Purchase::findOrFail($id);

        $pdfData = [
            'challan_number' => $purchase->id,
            'date_time' => $purchase->date_time,
            'receiver' => $purchase->receiver->name,
            'vehicle_number' => $purchase->vehicle->name,
            'gross_weight' => $purchase->gross_weight,
            'tare_weight' => $purchase->tare_weight,
            'net_weight' => $purchase->net_weight,
            'material' => $purchase->material->name,
        ];

        $pdf = PDF::loadView('purchase.pdf', $pdfData);

        return $pdf->download("Purchase.pdf");
    }
}
