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

class SalesController extends Controller
{
    public function index()
    {
        return view('sales.index');    
    }

    public function editIndex(Request $request)
    {
        $data = Sales::latest()->paginate(5);

        return view('sales.edit-index', compact('data'))
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
        return redirect()->route('sales.pendingLoad')
            ->with('success', 'Sales created successfully.');
    }

    public function pendingLoad()
    {
        $sales = Sales::with('vehicle')->latest()->paginate(5);
        return view('sales.pendingLoad-sales', compact('sales'));
    }

    public function show()
    {
        // $sales = Sales::all();
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
}
