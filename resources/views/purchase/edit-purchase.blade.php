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
                                        <a class="btn btn-sm btn-primary" href="{{ $purchase->status == '1' ? route('purchase.editIndex') : route('purchase.pendingLoad') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ route('purchase.update', $purchase->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                                <div class="card-body general-info">
                                    <div class="row align-items-center d-flex justify-content-between">
                                        <div class="col-lg-4 mb-4">
                                            <label for="challan_number" class="form-label mb-2">Challan Number </label>
                                            <div class="input-group">
                                                <div class="input-group-text">P_</div>
                                                <input type="text" name="id" placeholder="Challan Number" class="form-control"  id="challan_number"  value="{{ old('id', $purchase->id == '' ? 0+1 : $purchase) }}" readonly>
                                                @error('id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="date_time" class="form-label mb-2">Date</label> 
                                            <div class="input-group">
                                                <input type="datetime-local" name="date_time" class="form-control"  id="date"  value="{{ old('date_time', $purchase->date_time ?? '') }}" readonly>
                                            </div>
                                            @error('date_time')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="vehicle_id" class="form-label mb-2">Vehicle Number </label>
                                            <div class="input-group">
                                                <select class="form-select js-select2" aria-label="Default select example" id="vehicle_id" name="vehicle_id"  data-url="{{ route('purchaseVehicle.details') }}">
                                                    <option selected disabled value>Select Vehicle</option>
                                                    @foreach($vehicles as $vehicle)
                                                        <option value="{{ $vehicle->id ?? '' }}" {{ $vehicle->id == $purchase->vehicle_id ? 'selected' : '' }}>{{ $vehicle->name ?? '' }}</option>
                                                    @endforeach
                                                </select>                                                
                                            </div>
                                            @error('vehicle_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="transporter" class="form-label mb-2">Transporter Name</label>
                                            <div class="input-group">   
                                                <input type="text" name="transporter" placeholder="Transporter Name" class="form-control"  id="transporter"  value="{{ old('transporter', $purchase->transporter ?? '') }}" readonly>
                                            </div>
                                            @error('transporter')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="contact_number" class="form-label mb-2">Owner Contact Number</label>
                                            <div class="input-group">
                                                <input type="number" name="contact_number" maxlength="10" minlength="10"  placeholder="Enter Transporter Owner Contact Number" class="form-control" id="contact_number" value="{{ old('contact_number', $purchase->contact_number ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="driver_contact_number" class="form-label mb-2">Driver Contact Number</label>
                                            <div class="input-group">
                                                <input type="number" name="driver_contact_number" maxlength="10" minlength="10"  placeholder="Enter Driver Contact Number" class="form-control" id="driver_contact_number" value="{{ old('driver_contact_number', $purchase->driver_contact_number ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('driver_contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="gross_weight" class="form-label mb-2">Gross Weight</label>
                                            <div class="input-group">
                                                <input type="number" name="gross_weight" placeholder="Enter Gross Weight" class="form-control" id="gross_weight" value="{{ old('gross_weight', $purchase->gross_weight ?? '') }}">
                                            </div>
                                            @error('gross_weight')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="tare_weight" class="form-label mb-2">Tare Weight</label>
                                            <div class="input-group">
                                                <input type="number" name="tare_weight" placeholder="Enter Tare Weight" class="form-control" id="tare_weight" value="{{ old('tare_weight', $purchase->tare_weight ?? '') }}" readonly>
                                            </div>
                                            @error('tare_weight')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="net_weight" class="form-label mb-2">Net Weight</label>
                                            <div class="input-group">
                                                <input type="number" name="net_weight" placeholder="Enter Net Weight" class="form-control" id="net_weight" value="{{ old('net_weight', $purchase->net_weight ?? '') }}" readonly>
                                            </div>
                                            @error('net_weight')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="material_id" class="form-label mb-2">Material Name </label>
                                            <div class="input-group">
                                                <select class="form-select js-select2" aria-label="Default select example" id="material_id" name="material_id">
                                                    <option selected disabled value>Select Material</option>
                                                    @foreach($materials as $material)
                                                        <option value="{{ $material->id ?? '' }}" {{ $material->id == $purchase->material_id ? 'selected' : '' }}>{{ $material->name ?? '' }}</option>
                                                    @endforeach
                                                </select>                                                
                                            </div>
                                            @error('material_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="loading_id" class="form-label mb-2">Loading Name </label>
                                            <div class="input-group">
                                                <select class="form-select js-select2" aria-label="Default select example" id="loading_id" name="loading_id">
                                                    <option selected disabled value>Select Loading</option>
                                                    @foreach($loadings as $loading)
                                                        <option value="{{ $loading->id ?? '' }}" {{ $loading->id == $purchase->loading_id ? 'selected' : '' }}>{{ $loading->name ?? '' }}</option>
                                                    @endforeach
                                                </select>                                                
                                            </div>
                                            @error('loading_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="quarry_id" class="form-label mb-2">Quarry Name </label>
                                            <div class="input-group">
                                                <select class="form-select js-select2" aria-label="Default select example" id="quarry_id" name="quarry_id">
                                                    <option selected disabled value>Select Quarry</option>
                                                    @foreach($quarries as $quarry)
                                                        <option value="{{ $quarry->id ?? '' }}" {{ $quarry->id == $purchase->quarry_id ? 'selected' : '' }}>{{ $quarry->name ?? '' }}</option>
                                                    @endforeach
                                                </select>                                                
                                            </div>
                                            @error('quarry_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="receiver_id" class="form-label mb-2">Receiver Name </label>
                                            <div class="input-group">
                                                <select class="form-select js-select2" aria-label="Default select example" id="receiver_id" name="receiver_id">
                                                    <option selected disabled value>Select Receiver</option>
                                                    @foreach($purchaseReceivers as $receiver)
                                                        <option value="{{ $receiver->id ?? '' }}" {{ $receiver->id == $purchase->receiver_id ? 'selected' : '' }}>{{ $receiver->name ?? '' }}</option>
                                                    @endforeach
                                                </select>                                                
                                            </div>
                                            @error('receiver_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="driver_id" class="form-label mb-2">Driver Name </label>
                                            <div class="input-group">
                                                <select class="form-select js-select2" aria-label="Default select example" id="driver_id" name="driver_id">
                                                    <option selected disabled value>Select Driver</option>
                                                    @foreach($drivers as $driver)
                                                        <option value="{{ $driver['id'] ?? '' }}" {{ (isset($driver['original_id']) && $driver['original_id'] == $purchase->driver_id) || $driver['id'] == 'driver_' . $purchase->driver_id ? 'selected' : '' }}>{{ $driver['name'] ?? '' }}</option>
                                                    @endforeach
                                                </select>                                                
                                            </div>
                                            @error('driver_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="carting_id" class="form-label mb-2">Carting</label>
                                            <div class="input-group">
                                                <select class="form-select js-select2" aria-label="Default select example" id="carting_id" name="carting_id">
                                                    <option selected disabled value>Select Carting</option>
                                                    <option value="0" {{ $purchase->carting_id == 0 ? 'selected' : '' }}>Carting</option>
                                                    <option value="1" {{ $purchase->carting_id == 1 ? 'selected' : '' }}>Self</option>
                                                </select>                                                
                                            </div>
                                            @error('carting_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4"> 
                                            <label for="note">Note</label>
                                            <textarea name="note" class="form-control" id="note" placeholder="Note..">{{ old('note', $purchase->note ?? '') }}</textarea>
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
    <!---------------------------Vehicle Modal Start --------------------------->
        @can('add-purchaseVehicles')
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
    <!---------------------------Vehicle Modal Start --------------------------->
    <!---------------------------Material Modal Start --------------------------->
        @can('add-purchaseMaterials')
            <div class="modal fade" id="materialModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Material</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('purchaseMaterials.store') }}" method="POST" id="materialForm">
                            @csrf
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-12 mb-4">
                                        <label for="material_name" class="form-label mb-2">Material Name</label>
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="Enter Material Name" class="form-control"  id="material_name"  value="{{ old('name') }}">
                                        </div>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
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
    <!---------------------------Material Modal Ends--------------------------->
    <!---------------------------Loading Modal Start --------------------------->
        @can('add-purchaseLoading')
            <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Loading</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('purchaseLoading.store') }}" method="POST" id="loadingForm">
                            @csrf  
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-12 mb-4">
                                        <label for="loading_name" class="form-label mb-2">Loading Name</label>
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="Enter Loading Name" class="form-control"  id="loading_name"  value="{{ old('name') }}">
                                        </div>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3" id="loading_btn">
                                                <i class="bx bx-save me-2"></i>
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal" >
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
    <!---------------------------Loading Modal Ends--------------------------->
    <!---------------------------Quarry Modal Start --------------------------->
        @can('add-purchaseQuarry') 
            <div class="modal fade" id="quarryModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Quarry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('purchaseQuarry.store') }}" method="POST" id="purchaseQuarryForm">
                            @csrf  
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-12 mb-4">
                                        <label for="quarry_name" class="form-label mb-2">Quarry Name</label>
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="Enter Quarry Name" class="form-control"  id="quarry_name"  value="{{ old('name') }}">
                                        </div>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3" id="place_btn">
                                                <i class="bx bx-save me-2"></i>
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal" >
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
    <!---------------------------Quarry Modal Ends--------------------------->
    <!---------------------------Driver Modal Start --------------------------->
        @can('add-purchaseDriver')
            <div class="modal fade" id="driverModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Driver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('purchaseDriver.store') }}" method="POST" id="driverForm">
                            @csrf
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-12 mb-4">
                                        <label for="driver_name" class="form-label mb-2">Driver Name</label>
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="Enter Driver" class="form-control"  id="driver_name"  value="{{ old('name') }}">
                                        </div>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-lg-12 mb-4">
                                        <label for="driver" class="form-label mb-2">Driver Type</label>
                                        <div class="input-group">
                                            <select name="driver" id="driver" class="form-control">
                                                <option value="">Select Driver Type</option>
                                                <option value="self">Self</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        @error('driver')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row align-items-center">  
                                    <div class="col-lg-12 mb-4">
                                        <label for="driver_contact_number" class="form-label mb-2">Driver Contact Number </label>
                                        <div class="input-group">
                                            <input type="number" name="contact_number" placeholder="Enter Driver Contact Number" class="form-control" id="driver_contact_number" value="{{ old('contact_number') }}">
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
    <!---------------------------Driver Modal Ends--------------------------->
    <!---------------------------Receiver Modal Start --------------------------->
        @can('add-purchaseReceiver') 
            <div class="modal fade" id="receiverModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Receiver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('purchaseReceiver.store') }}" method="POST" id="receiverForm">
                            @csrf
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                            <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-6 mb-4">
                                        <label for="receiver_name" class="form-label mb-2">Receiver</label>
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="Enter Receiver Name" class="form-control"  id="receiver_name"  value="{{ old('name') }}">
                                        </div>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-lg-6 mb-4">
                                        <label for="name" class="form-label mb-2">Sales By</label>
                                        <div class="input-group">
                                            <select name="sales_by" id="" class="form-control">
                                                <option value="">Select Sales By</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}" >{{ $employee->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-6 mb-4">
                                        <label for="contact_number" class="form-label mb-2">Receiver Contact</label>
                                        <div class="input-group">
                                            <input type="number" name="contact_number" placeholder="Enter Receiver Contact Number" class="form-control"  id="contact_number"  value="{{ old('contact_number', $purchaseReceiver->contact_number ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                        </div>
                                        @error('contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <hr class="mt-0">
                                <h5>Persions</h5>
                                <div class="row align-items-center">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="myTable">
                                                <thead>
                                                    <tr class="align-items-center">
                                                        <th style="min-width: 250px;">Persion</th>
                                                        <th style="min-width: 300px;">Persion Contact Number</th>
                                                        <th><button id="addTableRow" type="button" class="btn btn-sm btn-success"><i class="bx bx-plus"></i> Add Row</button></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" class="form-control" name="persions[]" placeholder="Enter Persion Name"></td>
                                                        <td><input type="text" class="form-control" name="persion_contact_number[]" placeholder="Enter Persion Contact Number"></td>
                                                        <td><button class="btn btn-danger btn-md deleteRow"><i class="bx bx-trash"></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
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
    <!---------------------------Receiver Modal Ends--------------------------->
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
        
        // $(document).ready(function(){
        //     const dateTimeInput = document.getElementById('date');
        //     const now = new Date();

        //     now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        //     now.setSeconds(0);
        //     now.setMilliseconds(0);
            
        //     dateTimeInput.value = now.toISOString().slice(0, 16);
        // })


        $(document).ready(function() { 

            $('#addTableRow').click(function() {
                let newRow = `
                    <tr>
                        <td><input type="text" class="form-control" name="persions[]" placeholder="Enter Persion Name"></td>
                        <td><input type="text" class="form-control" name="persion_contact_number[]" placeholder="Enter Persion Contact Number"></td>
                        <td><button class="btn btn-danger btn-md deleteRow"><i class="bx bx-trash"></i></button></td>
                        
                    </tr>
                `;
                $('#myTable tbody').append(newRow);
                initSelect2($('#myTable tbody tr:last'));
            });

            $('#myTable').on('click', '.deleteRow', function() {
                $(this).closest('tr').remove();
            });

            function updateRowNumbers() {
                rowCount = 1;
                $('#myTable tbody tr').each(function() {
                    $(this).find('td:first').text(rowCount);
                    rowCount++;
                });
            }
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
            dropDownSelector: '#material_id',
            modalSelector: '#materialModal',
            formSelector: '#materialForm',
            fields: {
                id : '#material_id',
                name: '#material_name',
            }
        })
        modalDropdown({
            dropDownSelector: '#loading_id',
            modalSelector: '#loadingModal',
            formSelector: '#loadingForm',
            fields: {
                id : '#loading_id',
                name: '#loading_name',
            }
        })
        modalDropdown({
            dropDownSelector: '#quarry_id',
            modalSelector: '#quarryModal',
            formSelector: '#purchaseQuarryForm',
            fields: {
                id : '#quarry_id',
                name: '#quarry_name',
            }
        })
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
        modalDropdown({
            dropDownSelector: '#driver_id',
            modalSelector: '#driverModal',
            formSelector: '#driverForm',
            fields: {
                id : '#driver_id',
                name: '#driver_name',
            },
            labelField: 'name',
        })
        modalDropdown({
            dropDownSelector: '#receiver_id',
            modalSelector: '#receiverModal',
            formSelector: '#receiverForm',
            fields: {
                id : '#receiver_id',
                name: '#receiver_name',
            },
            labelField: 'name',
        })

        // Calculate net weight + show error UNDER the field
        $(document).ready(function() {
            const $gross = $('#gross_weight');
            const $tare = $('#tare_weight');
            const $net = $('#net_weight');

            // Create error div under the field (if not already)
            if ($('#tare-error').length === 0) {
                $tare.closest('.input-group').parent().append('<div id="tare-error" class="text-danger mt-1"></div>');
            }

            $('#gross_weight, #tare_weight').on('input', function() {
                let grossWeight = parseFloat($gross.val()) || 0;
                let tareWeight = parseFloat($tare.val()) || 0;

                // Validation: Tare must be smaller than Gross
                if (tareWeight >= grossWeight && grossWeight > 0) {
                    $('#tare-error').text('Tare Weight must be smaller than Gross Weight');
                    $gross.addClass('is-invalid');
                } else {
                    $('#tare-error').text('');
                    $gross.removeClass('is-invalid');
                }

                // Auto-calculate Net Weight only if valid
                if (grossWeight > 0 && tareWeight > 0 && tareWeight < grossWeight) {
                    $net.val((grossWeight - tareWeight).toFixed(2));
                } else {
                    $net.val('');
                }
            });
        });

    </script>
@endpush