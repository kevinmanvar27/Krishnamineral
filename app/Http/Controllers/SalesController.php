<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Materials;
use App\Models\Loading;
use App\Models\Places;
use App\Models\Party;
use App\Models\Royalty;
use App\Models\Driver;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::latest()->paginate(5);
        return view('sales.index', compact('sales'));    
    }

    public function editIndex(Request $request)
    {
        $sales = Sales::with('vehicle')->latest()->paginate(5);
        return view('sales.edit-index', compact('sales'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        
    }

    public function create(Sales $sales)
    {
        $sales = Sales::latest('id')->first();
        return view('sales.create-sales', compact('sales'));    
    }

    public function store(Request $request)
    { 
        $request->validate([
            'date_time' => 'required',
            'vehicle_id' => 'required',
            'transporter' => 'required',
            'tare_weight' => 'required',
            'contact_number' => 'required',
        ]);
        Sales::create($request->all());
        return redirect()->route('sales.index')
            ->with('success', 'Sales created successfully.');
    }

    public function pendingLoad()
    {
        $sales = Sales::with('vehicle')->latest()->paginate(5);
        return view('sales.pendingLoad-sales', compact('sales'));
    }

    public function show($id)
    {
        $sales = Sales::find($id);
        return view('sales.show', compact('sales'));
    }

    public function edit($id)
    {
        $sales = Sales::findOrFail($id);
        $materials = Materials::all();
        $loadings = Loading::all();
        $places = Places::all();
        $parties = Party::all();
        $royalties = Royalty::all();
        $drivers  = Driver::all();
        $employees = User::all();
        return view('sales.edit-sales', compact('sales', 'materials', 'loadings', 'places', 'parties', 'royalties', 'drivers', 'employees'));    
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'gross_weight' => 'required',
            'tare_weight' => 'required',
            'net_weight' => 'required',
            'material_id' => 'required|exists:materials,id',
            'loading_id' => 'required|exists:loadings,id',
            'place_id' => 'required|exists:places,id',
            'party_id' => 'required|exists:parties,id',
            'royalty_id' => 'nullable|exists:royalties,id',
            'royalty_number' => 'required',
            'royalty_tone' => 'required',
            'driver_id' => 'nullable|exists:drivers,id',
            'carting_id' => 'required',
            'note' => 'nullable',
        ]);

        if ($validated['gross_weight'] <= $validated['tare_weight']) {
            return redirect()->back()
                ->withErrors(['gross_weight' => 'Gross weight must be greater than tare weight'])
                ->withInput();
        }

        $validated['status'] = '1';
        Sales::findOrFail($id)->update($validated);

        // Save ID in session for PDF auto-download
        session([
            'pdf_sales_id' => $id,
            'auto_download_pdf' => true
        ]);

        return redirect()->route('sales.editIndex')
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

        $pdf = \PDF::loadView('sales.pdf', $pdfData);

        return $pdf->download("Sales.pdf");
    }

}
