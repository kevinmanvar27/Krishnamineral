<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Party;
use App\Models\PartyPersion;

class PartyController extends Controller
{
    public function index(Request $request)
    {
        $parties = Party::latest()->paginate(5);
        return view('party.index', compact('parties'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    public function create()
    {
        $employees = User::all();
        return view('party.add-edit', compact('employees'));
    }

    public function store(Request $request)
    { 
        $validated = $request->validate([
            'name' => 'required',
            'sales_by' => 'required',
            'persions' => 'required|array',
            'persions.*' => 'string',
            'persion_contact_number' => 'required|array',
            'persion_contact_number.*' => 'numeric|regex:/^\+?[0-9]{10,15}$/',
        ]);

        // Run transaction and return the created party
        $party = DB::transaction(function () use ($validated) {
            $partyData = collect($validated)->except([
                'persions',
                'persion_contact_number'
            ])->toArray();

            $party = Party::create($partyData);

            foreach ($validated['persions'] as $key => $personName) {
                if (PartyPersion::where('party_id', $party->id)
                                ->where('persions', $personName)
                                ->exists()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'persions' => ["The person '$personName' already exists for this party."]
                    ]);
                }

                PartyPersion::create([
                    'party_id' => $party->id,
                    'persions' => $personName,
                    'persion_contact_number' => $validated['persion_contact_number'][$key]
                ]);
            }

            return $party; // return party from transaction
        });

        // Return AJAX response if needed
        if ($request->ajax()) {
            return response()->json([
                'id' => $party->id,
                'name' => $party->name,
            ]);
        }

        return redirect()->route('party.index')->with('success', 'Party Added Successfully');
    }


    public function edit($id)
    {
        $party = Party::findOrFail($id);
        $employees = User::all();
        return view('party.add-edit', compact('party', 'employees'));
    }

    public function update(Request $request, Party $party)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string',
            'sales_by' => 'required|integer',

            'persions' => 'required|array',
            'persions.*' => 'string',
            'persion_contact_number' => 'required|array',
            'persion_contact_number.*' => 'numeric|regex:/^\+?[0-9]{10,15}$/',
        ]);

        DB::transaction(function () use ($validated, $party) {
            $party->update([
                'name' => $validated['name'],
                'sales_by' => $validated['sales_by'],
            ]);

            $party->items()->delete();

            // Insert/update new persons
            foreach ($validated['persions'] as $key => $personName) {
                // Ensure uniqueness per party
                $exists = PartyPersion::where('party_id', $party->id)
                            ->where('persions', $personName)
                            ->exists();

                if ($exists) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'persions' => ["The persion '$personName' already exists for this party."]
                    ]);
                }

                PartyPersion::create([
                    'party_id' => $party->id,
                    'persions' => $personName,
                    'persion_contact_number' => $validated['persion_contact_number'][$key]
                ]);
            }
        });

        return redirect()->route('party.editIndex')->with('success', 'Party Updated Successfully');
    }



    public function editIndex(Request $request)
    {
        $parties = Party::latest()->paginate(5);
        return view('party.edit-index' , compact('parties'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function show($id)
    {
        $parties = Party::with('items', 'salesPerson')->findOrFail($id);
        return view('party.show', compact('parties'));
    }

    // public function destroy($id)
    // {
    //     Party::findOrFail($id)->delete();
    //     return redirect()->route('party.index')->with('success', 'Party Deleted Successfully');
    // }
}
