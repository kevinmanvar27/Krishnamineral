@extends('layouts.app')

@section('content')

    <main class="wrapper">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success" role="alert"> 
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Vendor Details</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ isset($vendor) ? route('vendors.editIndex') : route('vendors.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ isset($vendor) ? route('vendors.update', $vendor->id) : route('vendors.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($vendor))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">    
                                    <div class="row">
                                        <!-- First Column -->
                                        <!-- Vendor Code -->
                                        <div class="col-md-4 mb-4">
                                            <label for="vendor_code" class="form-label mb-2">Vendor Code</label>
                                            <div class="input-group">
                                                <div class="input-group-text">VEN_</div>
                                                <input type="text" name="vendor_code" placeholder="Vendor Code" class="form-control" id="vendor_code" value="{{ old('vendor_code', (isset($vendor) ? $vendor->vendor_code : (isset($nextVendorCode) ? $nextVendorCode : ''))) }}" {{ isset($vendor) ? '' : 'readonly' }}>
                                            </div>
                                            @if ($errors->has('vendor_code'))<div class="text-danger mt-1">{{ $errors->first('vendor_code') }}</div>@endif
                                        </div>
                                        
                                        <!-- Vendor Name -->
                                        <div class="col-md-4 mb-4">
                                            <label for="vendor_name" class="form-label mb-2">Vendor Name</label>
                                            <div class="input-group">
                                                <input type="text" name="vendor_name" placeholder="Vendor Name" class="form-control" id="vendor_name" value="{{ old('vendor_name', $vendor->vendor_name ?? '') }}">
                                            </div>
                                            @error('vendor_name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>

                                        <!-- Contact Person -->
                                        <div class="col-md-4 mb-4">
                                            <label for="contact_person" class="form-label mb-2">Contact Person</label>
                                            <div class="input-group">
                                                <input type="number" name="contact_person" placeholder="Contact Person" class="form-control" id="contact_person" value="{{ old('contact_person', $vendor->contact_person ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('contact_person')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        
                                        <!-- Mobile -->
                                        <div class="col-md-6 mb-4">
                                            <label for="mobile" class="form-label mb-2">Mobile</label>
                                            <div class="input-group">
                                                <input type="number" name="mobile" placeholder="Mobile" class="form-control" id="mobile" value="{{ old('mobile', $vendor->mobile ?? '') }}" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('mobile')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <!-- Telephone -->
                                        <div class="col-md-6 mb-4">
                                            <label for="telephone" class="form-label mb-2">Telephone</label>
                                            <div class="input-group">
                                                <input type="number" name="telephone" placeholder="Telephone" class="form-control" id="telephone" value="{{ old('telephone', $vendor->telephone ?? '') }}" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('telephone')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                            
                                    <div class="row"> 
                                        <!-- Email ID -->
                                        <div class="col-md-6 mb-4">
                                            <label for="email_id" class="form-label mb-2">Email ID</label>
                                            <div class="input-group">
                                                <input type="email" name="email_id" placeholder="Email ID" class="form-control" id="email_id" value="{{ old('email_id', $vendor->email_id ?? '') }}">
                                            </div>
                                            @error('email_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        
                                        <!-- Website -->
                                        <div class="col-md-6 mb-4">
                                            <label for="website" class="form-label mb-2">Website</label>
                                            <div class="input-group">
                                                <input type="text" name="website" placeholder="Website" class="form-control" id="website" value="{{ old('website', $vendor->website ?? '') }}">
                                            </div>
                                            @error('website')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>       
                                        
                                    <div class="row">
                                        <!-- Country -->
                                        <div class="col-md-6 mb-4">
                                            <label for="country" class="form-label mb-2">Country</label>
                                            <div class="input-group">
                                                <input type="text" name="country" placeholder="Country" class="form-control" id="country" value="{{ old('country', $vendor->country ?? '') }}">
                                            </div>
                                            @error('country')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        
                                        <!-- State -->
                                        <div class="col-md-6 mb-4">
                                            <label for="state" class="form-label mb-2">State</label>
                                            <div class="input-group">
                                                <input type="text" name="state" placeholder="State" class="form-control" id="state" value="{{ old('state', $vendor->state ?? '') }}">
                                            </div>
                                            @error('state')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        
                                    </div>

                                    <div class="row">
                                        <!-- City -->
                                        <div class="col-md-6 mb-4">
                                            <label for="city" class="form-label mb-2">City</label>
                                            <div class="input-group">
                                                <input type="text" name="city" placeholder="City" class="form-control" id="city" value="{{ old('city', $vendor->city ?? '') }}">
                                            </div>
                                            @error('city')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                        <!-- Pincode -->
                                        <div class="col-md-6 mb-4">
                                            <label for="pincode" class="form-label mb-2">Pincode</label>
                                            <div class="input-group">
                                                <input type="text" name="pincode" placeholder="Pincode" class="form-control" id="pincode" value="{{ old('pincode', $vendor->pincode ?? '') }}" maxlength="10">
                                            </div>
                                            @error('pincode')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Address -->
                                        <div class="col-md-12 mb-4">
                                            <label for="address" class="form-label mb-2">Address</label>
                                            <div class="input-group">
                                                <textarea name="address" placeholder="Address" class="form-control" id="address" rows="3">{{ old('address', $vendor->address ?? '') }}</textarea>
                                            </div>
                                            @error('address')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <!-- Radio button to show/hide additional details -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h5>Bank Account</h5>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="show_additional_details" id="showAdditionalYes" value="yes">
                                                <label class="form-check-label" for="showAdditionalYes">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="show_additional_details" id="showAdditionalNo" value="no" checked>
                                                <label class="form-check-label" for="showAdditionalNo">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Details Section -->
                                    <div id="additionalDetailsSection" class="border border-2 shadow-md rounded p-3" style="display: none;" data-show-default="{{ (isset($vendor) && ($vendor->bank_proof || $vendor->payment_conditions || $vendor->visiting_card)) ? 'true' : 'false' }}">
                                        <div class="row">
                                            <!-- Bank Proof -->
                                            <div class="col-md-6 mb-4">
                                                <label for="bank_proof" class="form-label mb-2">Bank Proof</label>
                                                <div class="input-group">
                                                    <input type="file" name="bank_proof" placeholder="Bank Proof" class="form-control" id="bank_proof" accept=".jpg, .jpeg, .png, .pdf">
                                                </div>
                                                @error('bank_proof')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                            </div>

                                            <!-- Old Bank Proof -->
                                            <div class="col-md-6">
                                                <label for="old_bank_proof">Old Bank Proof</label>
                                                <div class="input-group">
                                                    @if(isset($vendor) && $vendor->bank_proof)
                                                        <a href="{{ asset('storage/vendors/' . $vendor->bank_proof) }}" target="_blank" class="badge bg-secondary text-white text-decoration-none view-file" data-bs-toggle="modal" data-bs-target="#imageDownload" data-file="{{ asset('storage/vendors/' . $vendor->bank_proof) }}">View File</a>
                                                    @else
                                                        <span class="text-muted">No file uploaded</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Payment Conditions -->
                                            <div class="col-md-6 mb-4">
                                                <label for="payment_conditions" class="form-label mb-2">Payment Conditions</label>
                                                <div class="input-group">
                                                    <textarea name="payment_conditions" placeholder="Payment Conditions" class="form-control" id="payment_conditions" rows="3">{{ old('payment_conditions', $vendor->payment_conditions ?? '') }}</textarea>
                                                </div>
                                                @error('payment_conditions')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                            </div>
                                            
                                            <!-- Visiting Card -->
                                            <div class="col-md-6 mb-4">
                                                <label for="visiting_card" class="form-label mb-2">Visiting Card</label>
                                                <div class="input-group">
                                                    <input type="text" name="visiting_card" placeholder="Visiting Card" class="form-control" id="visiting_card" value="{{ old('visiting_card', $vendor->visiting_card ?? '') }}">
                                                </div>
                                                @error('visiting_card')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                            
                                    <hr class="mt-0">
                                    <div class="row">
                                        <!-- Note -->
                                        <div class="col-md-12 mb-4">
                                            <label for="note" class="form-label mb-2">Note</label>
                                            <div class="input-group">
                                                <textarea name="note" placeholder="Note" class="form-control" id="note" rows="3">{{ old('note', $vendor->note ?? '') }}</textarea>
                                            </div>
                                            @error('note')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <div class="col-lg-12">
                                            <div class="input-group justify-content-end">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="bx bx-save me-2"></i>
                                                    {{ isset($vendor) ? 'Update' : 'Submit' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>
    
    <!-- Download Modal -->
    <div class="modal fade" id="imageDownload" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="file-preview">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" download id="downloadFile" class="btn btn-success">Download</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Handle radio button change for showing/hiding additional details
            const radioButtons = document.querySelectorAll('input[name="show_additional_details"]');
            const additionalDetailsSection = document.getElementById('additionalDetailsSection');
            const bankProofInput = document.getElementById('bank_proof');
            const paymentConditionsInput = document.getElementById('payment_conditions');
            const visitingCardInput = document.getElementById('visiting_card');
            
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'yes') {
                        additionalDetailsSection.style.display = 'block';
                        // Removed required attribute setting
                    } else {
                        additionalDetailsSection.style.display = 'none';
                        // Removed required attribute removal
                    }
                });
            });
            
            // Check if we should show the section by default
            // Using data attribute to pass PHP values to JavaScript
            const showByDefault = additionalDetailsSection.getAttribute('data-show-default');
            if (showByDefault === 'true') {
                document.getElementById('showAdditionalYes').checked = true;
                additionalDetailsSection.style.display = 'block';
                // Removed required attribute setting
            }
            
            // Handle form submission for new vendors to include the vendor code
            const vendorForm = document.querySelector('form');
            if (vendorForm && !document.querySelector('[name="vendor_code"]:not([disabled])')) {
                vendorForm.addEventListener('submit', function(e) {
                    // If vendor code input is disabled, create a hidden input with the value
                    const vendorCodeInput = document.getElementById('vendor_code');
                    if (vendorCodeInput && vendorCodeInput.disabled) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'vendor_code';
                        hiddenInput.value = vendorCodeInput.value;
                        vendorForm.appendChild(hiddenInput);
                    }
                });
            }
        });
        
        // Handle file preview in modal
        $(document).on('click', '.view-file', function() {
            const fileUrl = $(this).data('file');
            const fileExtension = fileUrl.split('.').pop().toLowerCase();
            
            if (['jpg', 'jpeg', 'png'].includes(fileExtension)) {
                $('#file-preview').html(`<img src="${fileUrl}" class="img-fluid" alt="File">`);
            } else {
                $('#file-preview').html(`<iframe src="${fileUrl}" class="w-100" style="height: 500px;"></iframe>`);
            }
            
            $('#downloadFile').attr('href', fileUrl);
            $('#downloadFile').attr('download', fileUrl.split('/').pop());
        });
    </script>
@endpush
