<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Vendor::query();

        // Add filters
        if ($request->vendor_code) {
            $query->where('vendor_code', 'like', '%' . $request->vendor_code . '%');
        }

        if ($request->vendor_name) {
            $query->where('vendor_name', 'like', '%' . $request->vendor_name . '%');
        }

        if ($request->contact_person) {
            $query->where('contact_person', 'like', '%' . $request->contact_person . '%');
        }

        if ($request->mobile) {
            $query->where('mobile', 'like', '%' . $request->mobile . '%');
        }

        if ($request->email) {
            $query->where('email_id', 'like', '%' . $request->email . '%');
        }

        $vendors = $query->latest()->paginate(10);
        
        // Get unique values for dropdowns
        $vendorCodes = Vendor::select('vendor_code')->distinct()->pluck('vendor_code');
        $vendorNames = Vendor::select('vendor_name')->distinct()->pluck('vendor_name');
        $contactPersons = Vendor::select('contact_person')->distinct()->pluck('contact_person');
        $mobileNumbers = Vendor::select('mobile')->distinct()->pluck('mobile');
        $emails = Vendor::select('email_id')->distinct()->pluck('email_id');

        if ($request->ajax()) {
            return view('vendors.index', compact('vendors', 'vendorCodes', 'vendorNames', 'contactPersons', 'mobileNumbers', 'emails'))
                ->with('i', ($vendors->currentPage() - 1) * $vendors->perPage());
        }

        return view('vendors.index', compact('vendors', 'vendorCodes', 'vendorNames', 'contactPersons', 'mobileNumbers', 'emails'))
            ->with('i', ($vendors->currentPage() - 1) * $vendors->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the last vendor code and generate the next one
        $lastVendor = Vendor::orderBy('id', 'desc')->first();
        $nextVendorCode = '1'; // Default if no vendors exist
        
        if ($lastVendor) {
            $lastCode = $lastVendor->vendor_code;
            $nextNumber = intval($lastCode) + 1;
            $nextVendorCode = strval($nextNumber);
        }
        
        return view('vendors.add-edit', compact('nextVendorCode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define base validation rules
        $rules = [
            'vendor_code' => 'required|string|max:20|unique:vendors',
            'vendor_name' => 'required|string|max:255',
            'contact_person' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'mobile' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'telephone' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'email_id' => 'required|email|max:255',
            'website' => 'required|string|max:255',
            'country' => 'required|string|max:55',
            'state' => 'required|string|max:55',
            'city' => 'required|string|max:55',
            'pincode' => 'required|string|max:10',
            'address' => 'required|string',
            'bank_proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'payment_conditions' => 'nullable|string',
            'visiting_card' => 'nullable|string|max:255',
            'note' => 'nullable|string',
            'show_additional_details' => 'nullable|string|in:yes,no'
        ];

        // Add conditional validation rules
        if ($request->show_additional_details == 'yes') {
            $rules['bank_proof'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
            $rules['payment_conditions'] = 'required|string';
            $rules['visiting_card'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prepare data
        $data = $request->except('bank_proof');
        
        // Handle file upload
        if ($request->hasFile('bank_proof')) {
            $filename = time() . '_' . uniqid() . '_' . $request->file('bank_proof')->getClientOriginalName();
            $request->file('bank_proof')->storeAs('vendors', $filename, 'public');
            $data['bank_proof'] = $filename;
        }

        Vendor::create($data);

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendors.add-edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        // Define base validation rules
        $rules = [
            'vendor_code' => 'required|string|max:20|unique:vendors,vendor_code,' . $id,
            'vendor_name' => 'required|string|max:255',
            'contact_person' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'mobile' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'telephone' => 'required|digits:10|regex:/^[0-9+\-\s]+$/',
            'email_id' => 'required|email|max:255',
            'website' => 'required|string|max:255',
            'country' => 'required|string|max:55',
            'state' => 'required|string|max:55',
            'city' => 'required|string|max:55',
            'pincode' => 'required|string|max:10',
            'address' => 'required|string',
            'bank_proof' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'payment_conditions' => 'nullable|string',
            'visiting_card' => 'nullable|string|max:255',
            'note' => 'nullable|string',    
            'show_additional_details' => 'nullable|string|in:yes,no'
        ];

        // Add conditional validation rules
        if ($request->show_additional_details == 'yes') {
            // For updates, if there's no existing bank proof and no new file is uploaded, make it required
            if (!$vendor->bank_proof && !$request->hasFile('bank_proof')) {
                $rules['bank_proof'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
            } elseif ($request->hasFile('bank_proof')) {
                // If a new file is being uploaded, validate it
                $rules['bank_proof'] = 'required|file|mimes:jpeg,png,jpg,pdf|max:2048';
            }
            $rules['payment_conditions'] = 'required|string';
            $rules['visiting_card'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Prepare data
        $data = $request->except('bank_proof');
        
        // Handle file upload
        if ($request->hasFile('bank_proof')) {
            // Delete old file if exists
            if ($vendor->bank_proof && Storage::disk('public')->exists('vendors/' . $vendor->bank_proof)) {
                Storage::disk('public')->delete('vendors/' . $vendor->bank_proof);
            }
            
            $filename = time() . '_' . uniqid() . '_' . $request->file('bank_proof')->getClientOriginalName();
            $request->file('bank_proof')->storeAs('vendors', $filename, 'public');
            $data['bank_proof'] = $filename;
        }

        $vendor->update($data);

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        
        // Delete file if exists
        if ($vendor->bank_proof && Storage::disk('public')->exists('vendors/' . $vendor->bank_proof)) {
            Storage::disk('public')->delete('vendors/' . $vendor->bank_proof);
        }
        
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully.');
    }

    /**
     * Display a listing of the resource for editing.
     */
    public function editIndex(Request $request)
    {
        $query = Vendor::query();

        // Add filters
        if ($request->vendor_code) {
            $query->where('vendor_code', 'like', '%' . $request->vendor_code . '%');
        }

        if ($request->vendor_name) {
            $query->where('vendor_name', 'like', '%' . $request->vendor_name . '%');
        }

        if ($request->contact_person) {
            $query->where('contact_person', 'like', '%' . $request->contact_person . '%');
        }

        if ($request->mobile) {
            $query->where('mobile', 'like', '%' . $request->mobile . '%');
        }

        if ($request->email) {
            $query->where('email_id', 'like', '%' . $request->email . '%');
        }

        $vendors = $query->latest()->paginate(10);
        
        // Get unique values for dropdowns
        $vendorCodes = Vendor::select('vendor_code')->distinct()->pluck('vendor_code');
        $vendorNames = Vendor::select('vendor_name')->distinct()->pluck('vendor_name');
        $contactPersons = Vendor::select('contact_person')->distinct()->pluck('contact_person');
        $mobileNumbers = Vendor::select('mobile')->distinct()->pluck('mobile');
        $emails = Vendor::select('email_id')->distinct()->pluck('email_id');

        if ($request->ajax()) {
            return view('vendors.editIndex', compact('vendors', 'vendorCodes', 'vendorNames', 'contactPersons', 'mobileNumbers', 'emails'))
                ->with('i', ($vendors->currentPage() - 1) * $vendors->perPage());
        }

        return view('vendors.editIndex', compact('vendors', 'vendorCodes', 'vendorNames', 'contactPersons', 'mobileNumbers', 'emails'))
            ->with('i', ($vendors->currentPage() - 1) * $vendors->perPage());
    }
}