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
                        @session('success')
                            <div class="alert alert-success" role="alert"> 
                                {{ $value }}
                            </div>
                        @endsession
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Drilling Details</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ isset($drilling) ? route('drilling.editIndex') : route('drilling.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ isset($drilling) ? route('drilling.update', $drilling->drilling_id) : route('drilling.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($drilling))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">    
                                    <div class="row">
                                        <!-- First Column -->
                                        <div class="col-lg-4">
                                            <!-- Drilling ID -->
                                            <div class="mb-4">
                                                <label for="drilling_id" class="form-label mb-2">Drilling ID</label>
                                                <div class="input-group">
                                                    <div class="input-group-text">DR_</div>
                                                    <input type="text" name="drilling_id" placeholder="Drilling ID" class="form-control" id="drilling_id" value="{{ old('drilling_id', isset($drilling) ? $drilling->drilling_id : (isset($latestDrilling->drilling_id) ? $latestDrilling->drilling_id + 1 : 0+1 )) }}" readonly>
                                                </div>
                                            </div>
                                            
                                            <!-- Drilling Name (Dropdown) -->
                                            <div class="mb-4">
                                                <label for="dri_id" class="form-label mb-2">Drilling Name</label>
                                                <div class="input-group">
                                                    <select name="dri_id" id="dri_id" class="form-control js-select2" required>
                                                        <option value="">Select Drilling Name</option>
                                                        @foreach($drillingNames as $drillingName)
                                                            <option value="{{ $drillingName->dri_id }}" {{ old('dri_id', (isset($drilling) ? $drilling->dri_id : '') == $drillingName->dri_id ? 'selected' : '') }}>
                                                                {{ $drillingName->d_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('dri_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            
                                            <!-- Note -->
                                            <div class="mb-4">
                                                <label for="d_notes" class="form-label mb-2">Note</label>
                                                <div class="input-group">
                                                    <textarea name="d_notes" placeholder="Enter Note" class="form-control" id="d_notes" rows="3">{{ old('d_notes', $drilling->d_notes ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Second Column -->
                                        <div class="col-lg-8">
                                            <!-- Date Time -->
                                            <div class="mb-4">
                                                <label for="date_time" class="form-label mb-2">Date Time</label>
                                                <div class="input-group">
                                                    <input type="datetime-local" name="date_time" class="form-control" id="date_time" value="{{ old('date_time', isset($drilling) ? date('Y-m-d\TH:i', strtotime($drilling->date_time)) : '') }}" required>
                                                </div>
                                                @error('date_time')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            
                                            <!-- Hole - FOOT - RATE = TOTAL (Dynamic Rows) -->
                                            <div class="mb-4">
                                                <div class="row mt-2 mb-2">
                                                    <div class="col-md-12 d-flex justify-content-between">
                                                        <label class="form-label mb-2">Hole</label>
                                                        <button type="button" class="btn btn-sm btn-success add-hole-row">
                                                            <i class="bx bx-plus"></i> Add Row
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="hole-rows">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Hole</th>
                                                                <th>FOOT</th>
                                                                <th>RATE</th>
                                                                <th>TOTAL</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="hole-rows-container">
                                                            <!-- Existing rows will be populated here -->
                                                            @if(isset($drilling) && !empty($drilling->hole))
                                                                @foreach($drilling->hole as $index => $holeItem)
                                                                    <tr class="hole-row">
                                                                        <td>
                                                                            <input type="number" step="0.01" name="hole[]" placeholder="Hole" class="form-control hole-name" value="{{ $holeItem['name'] ?? '' }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" step="0.01" name="hole_foot[]" placeholder="FOOT" class="form-control hole-foot" value="{{ $holeItem['foot'] ?? '' }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" step="0.01" name="hole_rate[]" placeholder="RATE" class="form-control hole-rate" value="{{ $holeItem['rate'] ?? '' }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" step="0.01" name="hole_total[]" placeholder="TOTAL" class="form-control hole-total" value="{{ $holeItem['total'] ?? '' }}" readonly>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-sm btn-danger remove-hole-row">
                                                                                <i class="bx bx-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                            <!-- Gross Total -->
                                            <div class="mb-4">
                                                <label for="gross_total" class="form-label mb-2">Gross Total</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="gross_total" placeholder="Gross Total" class="form-control" id="gross_total" value="{{ old('gross_total', $drilling->gross_total ?? '') }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row justify-content-between">
                                        <div class="col-lg-12">
                                            <div class="input-group justify-content-end">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="bx bx-save me-2"></i>
                                                    {{ isset($drilling) ? 'Update' : 'Submit' }}
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
    @can('add-drillingName')
            <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Add Drilling Name</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="drillingForm" action="{{ route('drilling-name.store') }}" method="POST">
                                @csrf
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-4">
                                        <label for="d_name" class="form-label mb-2">Drilling Name</label>
                                        <div class="input-group">
                                            <input type="text" name="d_name" placeholder="Enter Drilling Name" class="form-control"  id="d_name">
                                        </div>
                                        @error('d_name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-4">
                                        <label for="phone_no" class="form-label mb-2">Phone Number</label>
                                        <div class="input-group">
                                            <input type="number" name="phone_no" placeholder="Enter Phone Number" class="form-control"  id="phone_no" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                        </div>
                                        @error('phone_no')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 mb-4">
                                        <label for="status" class="form-label mb-2">Drilling Status</label>
                                        <div class="input-group">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        @error('status')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                <i class="bx bx-save me-2"></i>
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
@endsection

@push('scripts')
    <!-- JavaScript for dynamic rows and calculations -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add hole row button
            document.querySelector('.add-hole-row').addEventListener('click', function() {
                addHoleRow();
            });
            
            // Initialize with at least one row if none exist
            if (document.querySelectorAll('.hole-row').length === 0) {
                addHoleRow();
            }
            
            // Function to add a new hole row
            function addHoleRow() {
                // Create a new table row with the same structure as existing rows
                const newRow = document.createElement('tr');
                newRow.className = 'hole-row';
                newRow.innerHTML = `
                    <td>
                        <input type="number" step="0.01" name="hole[]" placeholder="Hole" class="form-control hole-name">
                    </td>
                    <td>
                        <input type="number" step="0.01" name="hole_foot[]" placeholder="FOOT" class="form-control hole-foot">
                    </td>
                    <td>
                        <input type="number" step="0.01" name="hole_rate[]" placeholder="RATE" class="form-control hole-rate">
                    </td>
                    <td>
                        <input type="number" step="0.01" name="hole_total[]" placeholder="TOTAL" class="form-control hole-total" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-hole-row">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                `;
                
                // Add event listeners for calculation
                const holeInput = newRow.querySelector('.hole-name');
                const footInput = newRow.querySelector('.hole-foot');
                const rateInput = newRow.querySelector('.hole-rate');
                
                // Add event listeners for calculation
                [holeInput, footInput, rateInput].forEach(input => {
                    input.addEventListener('input', calculateHoleTotal);
                });
                
                // Add event listener for remove button
                newRow.querySelector('.remove-hole-row').addEventListener('click', function() {
                    newRow.remove();
                    calculateGrossTotal();
                });
                
                document.querySelector('.hole-rows-container').appendChild(newRow);
            }
            
            // Function to calculate hole total
            function calculateHoleTotal(event) {
                const row = event.target.closest('.hole-row');
                const hole = parseFloat(row.querySelector('.hole-name').value) || 0;
                const foot = parseFloat(row.querySelector('.hole-foot').value) || 0;
                const rate = parseFloat(row.querySelector('.hole-rate').value) || 0;
                const total = hole * foot * rate;
                row.querySelector('.hole-total').value = total.toFixed(2);
                calculateGrossTotal();
            }
            
            // Function to calculate gross total
            function calculateGrossTotal() {
                let grossTotal = 0;
                
                // Add hole totals
                document.querySelectorAll('.hole-total').forEach(input => {
                    grossTotal += parseFloat(input.value) || 0;
                });
                
                document.getElementById('gross_total').value = grossTotal.toFixed(2);
            }
            
            // Initialize calculations on page load
            calculateGrossTotal();
            
            // Set current date/time for new records if not already set
            const dateTimeInput = document.getElementById('date_time');
            if (!dateTimeInput.value && !dateTimeInput.hasAttribute('readonly')) {
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                now.setSeconds(0);
                now.setMilliseconds(0);
                dateTimeInput.value = now.toISOString().slice(0, 16);
            }
            
            // Add event listeners to existing hole rows
            document.querySelectorAll('.hole-row').forEach(row => {
                const holeInput = row.querySelector('.hole-name');
                const footInput = row.querySelector('.hole-foot');
                const rateInput = row.querySelector('.hole-rate');
                
                if (holeInput && footInput && rateInput) {
                    [holeInput, footInput, rateInput].forEach(input => {
                        input.addEventListener('input', calculateHoleTotal);
                    });
                }
                
                const removeButton = row.querySelector('.remove-hole-row');
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        row.remove();
                        calculateGrossTotal();
                    });
                }
            });
        });

        function modalDropdown({ dropDownSelector, modalSelector, formSelector, fields, labelField = 'name' })
        {
            const dropdown = document.querySelector(dropDownSelector);
            const modalElement = document.querySelector(modalSelector);
            if (!modalElement) return;
            const modal = new bootstrap.Modal(modalElement);
            
            $(dropDownSelector).select2();

            $(document).on('keydown', function(e) {
                const activeElement = document.activeElement;
                const select2Container = document.querySelector(`${dropDownSelector} + .select2-container .select2-selection`);
                const isFocused = activeElement === select2Container;

                if (isFocused && (e.key === 'Insert' || (e.key.toLowerCase() === 'i' && e.ctrlKey))) {
                    e.preventDefault();
                    modal.show();
                }
            });

            $(document).on('submit', formSelector, function(e) { 
                e.preventDefault();

                const form = $(this);
                const url = form.attr('action');
                const formData = new FormData(this);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) { 
                        for(const [responseKey, selector] of Object.entries(fields)){
                            if (response[responseKey] !== undefined)
                            {
                                const element = $(selector);
                                if (element.is('select'))
                                {
                                    const label = labelField ? (response[labelField] ?? response[responseKey]) : response[responseKey];
                                    element.append(`<option value="${response[responseKey]}">${label}</option>`);
                                    element.val(response[responseKey]);
                                }
                                else
                                {
                                    element.val(response[responseKey]);
                                }
                            }
                        }
                        modal.hide();
                        form[0].reset();
                    },
                    error: function(response){
                        $(formSelector).find(".print-error-msg").find("ul").html('');
                        $(formSelector).find(".print-error-msg").css('display','block');
                        $.each( response.responseJSON.errors, function( key, value ) {
                            $(formSelector).find(".print-error-msg").find("ul").append('<li>'+value+'</li>');
                        });
                    },
                });
            });
        }

        // Add modal dropdown for drilling name
        modalDropdown({
            dropDownSelector: '#dri_id',
            modalSelector: '#myModal',
            formSelector: '#drillingForm',
            fields: {
                dri_id: '#dri_id',
                d_name: '#d_name',
            },
            labelField: 'd_name',
        })
    </script>
@endpush