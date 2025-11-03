@extends('layouts.app')

@section('content')

    <div class="wrapper">
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
                                <h5 class="card-title">Purchase</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('purchase.pendingLoad') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ route('purchase.store') }}" enctype="multipart/form-data">
                            @csrf
                                <div class="card-body general-info">
                                    <div class="row align-items-center d-flex justify-content-between">
                                        <div class="col-lg-4 mb-4">
                                            <label for="challan_number" class="form-label mb-2">Challan Number </label>
                                            <div class="input-group">
                                                <div class="input-group-text">P_</div>
                                                <input type="text" name="id" placeholder="Challan Number" class="form-control"  id="challan_number"  value="{{ old('id', isset($latestPurchase->id) == '' ? 0+1 : $latestPurchase->id+1) }}" readonly>
                                                @error('id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="date_time" class="form-label mb-2">Date</label>
                                            <div class="input-group">
                                                <input type="datetime-local" name="date_time" class="form-control"  id="date"  value="{{ old('date_time') }}" readonly>
                                                @error('date_time')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="vehicle_id" class="form-label mb-2">Vehicle Number </label>
                                            <div class="input-group">
                                                <select class="form-select js-select2" aria-label="Default select example" id="vehicle_id" name="vehicle_id"  data-url="{{ route('purchaseVehicle.details') }}">
                                                    <option selected disabled value>Select Vehicle</option>
                                                    @foreach($vehicles as $vehicle)
                                                        <option value="{{ $vehicle->id ?? '' }}">{{ $vehicle->name ?? '' }}</option>
                                                    @endforeach
                                                </select>                                                
                                            </div>
                                            @error('vehicle_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="transporter" class="form-label mb-2">Transporter Name</label>
                                            <div class="input-group">   
                                                <input type="text" name="transporter" placeholder="Transporter Name" class="form-control"  id="transporter"  value="{{ old('transporter') }}" readonly>
                                            </div>
                                            @error('transporter')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="contact_number" class="form-label mb-2">Owner Contact Number</label>
                                            <div class="input-group">
                                                <input type="number" name="contact_number" maxlength="10" minlength="10"  placeholder="Enter Transporter Owner Contact Number" class="form-control" id="contact_number" value="{{ old('contact_number') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="driver_contact_number" class="form-label mb-2">Driver Contact Number</label>
                                            <div class="input-group">
                                                <input type="number" name="driver_contact_number" maxlength="10" minlength="10"  placeholder="Enter Driver Contact Number" class="form-control" id="driver_contact_number" value="{{ old('driver_contact_number') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('driver_contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="tare_weight" class="form-label mb-2">Tare Weight</label>
                                            <div class="input-group">
                                                <input type="number" name="tare_weight" placeholder="Enter Tare Weight" class="form-control" id="tare_weight" value="{{ old('tare_weight') }}">
                                            </div>
                                            @error('tare_weight')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="bx bx-save me-2"></i>
                                                    Submit
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
        </div>
    </div>

    @can('add-vehicle')
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shortcutModalLabel">Vehicle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('purchaseVehicle.store') }}" method="POST" id="vehicleForm">
                        @csrf
                            <div class="alert alert-danger print-error-msg" style="display:none">
                                <ul></ul>
                            </div>
                            <div class="row align-items-center d-flex justify-content-between">
                                <div class="col-lg-12 mb-4">
                                    <label for="name" class="form-label mb-2">Vehicle Number</label>
                                    <div class="input-group">
                                        <input type="text" name="name" placeholder="Enter Vehicle Number" class="form-control"  id="name"  value="{{ old('name') }}">
                                    </div>
                                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-12 mb-4">
                                    <label for="vehicle_name" class="form-label mb-2">Transporter Name</label>
                                    <div class="input-group">
                                        <input type="text" name="vehicle_name" placeholder="Enter Transporter Name" class="form-control"  id="vehicle_name"  value="{{ old('vehicle_name') }}">
                                    </div>
                                    @error('vehicle_name')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>  
                            </div>
                            <div class="row align-items-center">  
                                <div class="col-lg-12 mb-4">
                                    <label for="transporter_contact_number" class="form-label mb-2">Transporter Contact Number </label>
                                    <div class="input-group">
                                        <input type="number" name="contact_number" placeholder="Enter Transporter Contact Number" class="form-control" id="transporter_contact_number" value="{{ old('transporter_contact_number') }}">
                                    </div>
                                    @error('contact_number')<div class="text-danger">{{ $message }}</div>@enderror
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
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal">
                                        <i class="bx bx-x me-1"></i>
                                            Close
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
    <script>        
        $(document).ready(function(){            
            const input = document.getElementById('name');
            input.addEventListener('input', formatVehicleNumber);

            function formatVehicleNumber(event) {
                let value = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                let formattedValue = '';
                
                if (value.length > 0) {
                formattedValue += value.substring(0, 2);
                }
                if (value.length > 2) {
                formattedValue += '-' + value.substring(2, 4);
                }
                if (value.length > 4) {
                formattedValue += '-' + value.substring(4, 6);
                }
                if (value.length > 6) {
                formattedValue += '-' + value.substring(6, 10);
                }
                
                event.target.value = formattedValue.trim();
            }
        });
        
        $(document).ready(function(){
            $('#vehicle_id').change(function() {
                const selectedValue = $(this).val();
                const url = $(this).data('url');

                if (selectedValue) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { id: selectedValue },
                        dataType: 'json',
                        success: function(data){
                            $('#transporter').val(data.vehicle_name);
                            $('#contact_number').val(data.contact_number);
                        },
                        error: function(xhr) {
                            console.log("Error:", xhr.responseText);
                        }
                    });
                }
            });
        });
        
        $(document).ready(function(){
            const dateTimeInput = document.getElementById('date');
            const now = new Date();

            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            now.setSeconds(0);
            now.setMilliseconds(0);
            
            dateTimeInput.value = now.toISOString().slice(0, 16);
        })

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

        modalDropdown({
            dropDownSelector: '#vehicle_id',
            modalSelector: '#myModal',
            formSelector: '#vehicleForm',
            fields: {
                id : '#vehicle_id',
                vehicle_name: '#transporter',
                contact_number: '#contact_number',
            },
            labelField: 'name',
        })
    </script>
    
@endpush