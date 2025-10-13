<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseReceiver;
use App\Models\PurchaseReceiverPersion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class PurchaseReceiverController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseReceiver::query();

        $query->where('table_type', 'purchase');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $purchaseReceivers = $query->latest()->paginate(5);

        $allNames = PurchaseReceiver::where('table_type', 'purchase')->select('name')->distinct()->pluck('name');
        $allDates = PurchaseReceiver::where('table_type', 'purchase')->select('created_at')->distinct()->get()->map(fn($d) => $d->created_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('purchaseReceiver.index', compact('purchaseReceivers', 'allNames', 'allDates'))
                ->with('i', ($purchaseReceivers->currentPage() - 1) * $purchaseReceivers->perPage());
        }

        return view('purchaseReceiver.index', compact('purchaseReceivers', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    public function create()
    {
        $employees = User::all();
        return view('purchaseReceiver.add-edit', compact('employees'));
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'name' => 'required',
            'contact_number' => 'required|regex:/^\+?[0-9]{10,15}$/',
            'sales_by' => 'required',
            'persions' => 'required|array',
            'persions.*' => 'string',
            'persion_contact_number' => 'required|array',
            'persion_contact_number.*' => 'numeric|regex:/^\+?[0-9]{10,15}$/',
        ]);

        // Run transaction and return the created purchaseReceiver
        $purchaseReceiver = DB::transaction(function () use ($validated) {
            $purchaseReceiverData = collect($validated)->except([
                'persions',
                'persion_contact_number'
            ])->toArray();
            $purchaseReceiverData['table_type'] = 'purchase';
            $purchaseReceiver = PurchaseReceiver::create($purchaseReceiverData);

            foreach ($validated['persions'] as $key => $personName) {
                if (PurchaseReceiverPersion::where('receiver_id', $purchaseReceiver->id)
                                ->where('persions', $personName)
                                ->exists()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'persions' => ["The person '$personName' already exists for this purchase receiver."]
                    ]);
                }

                PurchaseReceiverPersion::create([
                    'receiver_id' => $purchaseReceiver->id,
                    'persions' => $personName,
                    'persion_contact_number' => $validated['persion_contact_number'][$key]
                ]);
            }

            return $purchaseReceiver; // return purchaseReceiver from transaction
        });

        // Return AJAX response if needed
        if ($request->ajax()) {
            return response()->json([
                'id' => $purchaseReceiver->id,
                'name' => $purchaseReceiver->name,
            ]);
        }

        return redirect()->route('purchaseReceiver.index')->with('success', 'Purchase Receiver Added Successfully');
    }


    public function edit($id)
    {
        $purchaseReceiver = PurchaseReceiver::findOrFail($id);
        $employees = User::all();
        return view('purchaseReceiver.add-edit', compact('purchaseReceiver', 'employees'));
    }

    public function update(Request $request, PurchaseReceiver $purchaseReceiver)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string',
            'contact_number' => 'required|regex:/^\+?[0-9]{10,15}$/',
            'sales_by' => 'required|integer',

            'persions' => 'required|array',
            'persions.*' => 'string',
            'persion_contact_number' => 'required|array',
            'persion_contact_number.*' => 'numeric|regex:/^\+?[0-9]{10,15}$/',
        ]);

        DB::transaction(function () use ($validated, $purchaseReceiver) {
            $purchaseReceiver->update([
                'name' => $validated['name'],
                'contact_number' => $validated['contact_number'],
                'sales_by' => $validated['sales_by'],
                'table_type' => 'purchase',
            ]);

            $purchaseReceiver->items()->delete();

            // Insert/update new persons
            foreach ($validated['persions'] as $key => $personName) {
                // Ensure uniqueness per purchaseReceiver
                $exists = PurchaseReceiverPersion::where('receiver_id', $purchaseReceiver->id)
                            ->where('persions', $personName)
                            ->exists();

                if ($exists) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'persions' => ["The persion '$personName' already exists for this purchase receiver."]
                    ]);
                }

                PurchaseReceiverPersion::create([
                    'receiver_id' => $purchaseReceiver->id,
                    'persions' => $personName,
                    'persion_contact_number' => $validated['persion_contact_number'][$key]
                ]);
            }
        });

        return redirect()->route('purchaseReceiver.editIndex')->with('success', 'Purchase Receiver Updated Successfully');
    }

    public function editIndex(Request $request)
    {
        $query = PurchaseReceiver::query();
        
        $query->where('table_type', 'purchase');

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->date) {
            $query->whereDate('updated_at', $request->date);
        }

        $purchaseReceivers = $query->latest()->paginate(5);

        $allNames = PurchaseReceiver::where('table_type', 'purchase')->select('name')->distinct()->pluck('name');
        $allDates = PurchaseReceiver::where('table_type', 'purchase')->select('updated_at')->distinct()->get()->map(fn($d) => $d->updated_at->format('Y-m-d'));

        if ($request->ajax()) {
            return view('purchaseReceiver.edit-index', compact('purchaseReceivers', 'allNames', 'allDates'))
                ->with('i', ($purchaseReceivers->currentPage() - 1) * $purchaseReceivers->perPage());
        }


        return view('purchaseReceiver.edit-index' , compact('purchaseReceivers', 'allNames', 'allDates'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function show($id)
    {
        $purchaseReceivers = PurchaseReceiver::with('items', 'salesPerson')->findOrFail($id);
        return view('purchaseReceiver.show', compact('purchaseReceivers'));
    }
}
