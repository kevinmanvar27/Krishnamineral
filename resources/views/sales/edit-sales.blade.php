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
                                    <h5 class="card-title">Sales</h5>
                                    <div class="card-header-action">
                                        <div class="card-header-btn">         
                                            <a class="btn btn-sm btn-primary" href="{{ route('sales.index') }}">
                                                <i class="bx bx-arrow-to-left"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-0">
                                <form method="POST" action="{{ route('sales.update', $sales->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                    <div class="card-body general-info">
                                        <div class="row align-items-center d-flex justify-content-between">
                                            <div class="col-lg-4 mb-4">
                                                <label for="challan_number" class="form-label mb-2">Challan Number </label>
                                                <div class="input-group">
                                                    <div class="input-group-text">S_</div>
                                                    <input type="text" name="id" placeholder="Challan Number" class="form-control"  id="challan_number"  value="{{ old('id', $sales->id == '' ? 0+1 : $sales->id+1) }}" readonly>
                                                    @error('id')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="date_time" class="form-label mb-2">Date</label> 
                                                <div class="input-group">
                                                    <input type="datetime-local" name="date_time" class="form-control"  id="date"  value="{{ old('date_time', $sales->date_time ?? '') }}" readonly>
                                                    @error('date_time')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-4 mb-4">
                                                <label for="vehicle_id" class="form-label mb-2">Vehicle Number </label>
                                                <div class="input-group">
                                                    <select class="form-select" aria-label="Default select example" id="vehicle_id" name="vehicle_id"  data-url="{{ route('vehicle.details') }}">
                                                        <option selected disabled value>Select Vehicle</option>
                                                        @foreach($vehicles as $vehicle)
                                                            <option value="{{ $vehicle->id ?? '' }}" {{ $vehicle->id == $sales->vehicle_id ? 'selected' : '' }}>{{ $vehicle->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>                                                
                                                </div>
                                                @error('vehicle_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="transporter" class="form-label mb-2">Transporter Name</label>
                                                <div class="input-group">   
                                                    <input type="text" name="transporter" placeholder="Transporter Name" class="form-control"  id="transporter"  value="{{ old('transporter', $sales->transporter ?? '') }}" readonly>
                                                </div>
                                                @error('transporter')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>  
                                            <div class="col-lg-4 mb-4">
                                                <label for="contact_number" class="form-label mb-2">Contact Number</label>
                                                <div class="input-group">
                                                    <input type="number" name="contact_number" maxlength="10" minlength="10"  placeholder="Enter Transporter Contact Number" class="form-control" id="contact_number" value="{{ old('contact_number', $sales->contact_number ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                                </div>
                                                @error('contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-4 mb-4">
                                                <label for="gross_weight" class="form-label mb-2">Gross Weight</label>
                                                <div class="input-group">
                                                    <input type="number" name="gross_weight" placeholder="Enter Gross Weight" class="form-control" id="gross_weight" value="{{ old('gross_weight', $sales->gross_weight ?? '') }}">
                                                </div>
                                                @error('gross_weight')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="tare_weight" class="form-label mb-2">Tare Weight</label>
                                                <div class="input-group">
                                                    <input type="number" name="tare_weight" placeholder="Enter Tare Weight" class="form-control" id="tare_weight" value="{{ old('tare_weight', $sales->tare_weight ?? '') }}" readonly>
                                                    @error('tare_weight')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="net_weight" class="form-label mb-2">Net Weight</label>
                                                <div class="input-group">
                                                    <input type="number" name="net_weight" placeholder="Enter Net Weight" class="form-control" id="net_weight" value="{{ old('net_weight', $sales->net_weight ?? '') }}" readonly>
                                                </div>
                                                @error('net_weight')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-4 mb-4">
                                                <label for="material_id" class="form-label mb-2">Material Name </label>
                                                <div class="input-group">
                                                    <select class="form-select" aria-label="Default select example" id="material_id" name="material_id">
                                                        <option selected disabled value>Select Material</option>
                                                        @foreach($materials as $material)
                                                            <option value="{{ $material->id ?? '' }}" {{ $material->id == $sales->material_id ? 'selected' : '' }}>{{ $material->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>                                                
                                                </div>
                                                @error('material_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="loading_id" class="form-label mb-2">Loading Name </label>
                                                <div class="input-group">
                                                    <select class="form-select" aria-label="Default select example" id="loading_id" name="loading_id">
                                                        <option selected disabled value>Select Loading</option>
                                                        @foreach($loadings as $loading)
                                                            <option value="{{ $loading->id ?? '' }}" {{ $loading->id == $sales->loading_id ? 'selected' : '' }}>{{ $loading->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>                                                
                                                </div>
                                                @error('loading_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="place_id" class="form-label mb-2">Place Name </label>
                                                <div class="input-group">
                                                    <select class="form-select" aria-label="Default select example" id="place_id" name="place_id">
                                                        <option selected disabled value>Select Place</option>
                                                        @foreach($places as $place)
                                                            <option value="{{ $place->id ?? '' }}" {{ $place->id == $sales->place_id ? 'selected' : '' }}>{{ $place->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>                                                
                                                </div>
                                                @error('place_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-4 mb-4">
                                                <label for="party_id" class="form-label mb-2">Party Name </label>
                                                <div class="input-group">
                                                    <select class="form-select" aria-label="Default select example" id="party_id" name="party_id">
                                                        <option selected disabled value>Select Party</option>
                                                        @foreach($parties as $party)
                                                            <option value="{{ $party->id ?? '' }}" {{ $party->id == $sales->party_id ? 'selected' : '' }}>{{ $party->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>                                                
                                                </div>
                                                @error('party_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="royalty_id" class="form-label mb-2">Royalty Name </label>
                                                <div class="input-group">
                                                    <select class="form-select" aria-label="Default select example" id="royalty_id" name="royalty_id">
                                                        <option value="">NO</option>
                                                        @foreach($royalties as $royalty)
                                                            <option value="{{ $royalty->id ?? '' }}" {{ $royalty->id == $sales->royalty_id ? 'selected' : '' }}>{{ $royalty->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>                                                
                                                </div>
                                                @error('royalty_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="royalty_number" class="form-label mb-2">Royalty Number </label>
                                                <div class="input-group"> 
                                                    <input type="text" name="royalty_number" placeholder="Enter Royalty Number" class="form-control" id="royalty_number" value="{{ old('royalty_number', $sales->royalty_number ?? '') }}">
                                                    @error('royalty_number')<div class="text-danger">{{ $message }}</div>@enderror                  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-4 mb-4">
                                                <label for="royalty_tone" class="form-label mb-2">Royalty Tone</label>
                                                <div class="input-group"> 
                                                    <input type="text" name="royalty_tone" placeholder="Enter Royalty Tone" class="form-control" id="royalty_tone" value="{{ old('royalty_tone', $sales->royalty_tone ?? '') }}">
                                                    @error('royalty_tone')<div class="text-danger">{{ $message }}</div>@enderror                  
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="driver_id" class="form-label mb-2">Driver Name </label>
                                                <div class="input-group">
                                                    <select class="form-select select2" aria-label="Default select example" id="driver_id" name="driver_id">
                                                        <option selected disabled value>Select Driver</option>
                                                        @foreach($drivers as $driver)
                                                            <option value="{{ $driver->id ?? '' }}" {{ $driver->id == $sales->royalty_id ? 'selected' : '' }}>{{ $driver->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>                                                
                                                </div>
                                                @error('driver_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-lg-4 mb-4">
                                                <label for="carting_id" class="form-label mb-2">Carting</label>
                                                <div class="input-group">
                                                    <select class="form-select" aria-label="Default select example" id="carting_id" name="carting_id">
                                                        <option selected disabled value>Select Carting</option>
                                                        <option value="0" {{ $sales->carting_id == 0 ? 'selected' : '' }}>Carting</option>
                                                        <option value="1" {{ $sales->carting_id == 1 ? 'selected' : '' }}>Self</option>
                                                    </select>                                                
                                                </div>
                                                @error('carting_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-lg-4 mb-4"> 
                                                <label for="note">Note</label>
                                                <textarea name="note" class="form-control" id="note" placeholder="Note..">{{ old('note', $sales->note ?? '') }}</textarea>
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
        @can('add-vehicle')
            <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Vehicle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('vehicle.store') }}" method="POST" id="vehicleForm">
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
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal">
                                            <i class="fa-solid fa-close me-2"></i>
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
        @can('add-material')
            <div class="modal fade" id="materialModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Material</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('materials.store') }}" method="POST" id="materialForm">
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
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal">
                                            <i class="fa-solid fa-close me-2"></i>
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
        @can('add-loading')
            <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Loading</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('loading.store') }}" method="POST" id="loadingForm">
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
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3" id="loading_btn">
                                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal" >
                                            <i class="fa-solid fa-close me-2"></i>
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
        <!---------------------------Place Modal Start --------------------------->
        @can('add-place') 
            <div class="modal fade" id="placeModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Place</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('places.store') }}" method="POST" id="placeForm">
                            @csrf  
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-12 mb-4">
                                        <label for="place_name" class="form-label mb-2">Place Name</label>
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="Enter Place Name" class="form-control"  id="place_name"  value="{{ old('name') }}">
                                        </div>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3" id="place_btn">
                                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal" >
                                            <i class="fa-solid fa-close me-2"></i>
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
        <!---------------------------Place Modal Ends--------------------------->
        <!---------------------------Royalty Modal Start --------------------------->
        @can('add-royalty')
            <div class="modal fade" id="royaltyModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Royalty</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('royalty.store') }}" method="POST" id="royaltyForm">
                            @csrf  
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                                <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-12 mb-4">
                                        <label for="royalty_name" class="form-label mb-2">Royalty Name</label>
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="Enter Royalty Name" class="form-control"  id="royalty_name"  value="{{ old('name') }}">
                                        </div>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row justify-content-between">
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3" id="place_btn">
                                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal" >
                                            <i class="fa-solid fa-close me-2"></i>
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
        <!---------------------------Royalty Modal Ends--------------------------->
        <!---------------------------Driver Modal Start --------------------------->
        @can('add-driver')
            <div class="modal fade" id="driverModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Driver</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('driver.store') }}" method="POST" id="driverForm">
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
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal">
                                            <i class="fa-solid fa-close me-2"></i>
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
        <!---------------------------Party Modal Start --------------------------->
        @can('add-party') 
            <div class="modal fade" id="partyModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shortcutModalLabel">Party</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('party.store') }}" method="POST" id="partyForm">
                            @csrf
                                <div class="alert alert-danger print-error-msg" style="display:none">
                                    <ul></ul>
                                </div>
                            <div class="row align-items-center d-flex justify-content-between">
                                    <div class="col-lg-6 mb-4">
                                        <label for="party_name" class="form-label mb-2">Party</label>
                                        <div class="input-group">
                                            <input type="text" name="name" placeholder="Enter Party Name" class="form-control"  id="party_name"  value="{{ old('name') }}">
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
                                <hr class="mt-0">
                                <h5>Party Persions</h5>
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
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <button type="button" class="btn btn-secondary mt-2 mb-3" data-bs-dismiss="modal">
                                            <i class="fa-solid fa-close me-2"></i>
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

        $(document).ready(function() {
            function toggleRoyaltyReadonly() {
                const selectedValue = $('#royalty_id').val();
                if (selectedValue == "") {
                    $('#royalty_number').prop('readonly', true).val('No');
                    $('#royalty_tone').prop('readonly', true).val('No');
                } 
                else {
                    $('#royalty_number').prop('readonly', false).val();
                    $('#royalty_tone').prop('readonly', false).val();
                }   
            }

            toggleRoyaltyReadonly();

            $('#royalty_id').change(function() {
                toggleRoyaltyReadonly();
            });
        });

        $(document).ready(function () {
            $('#gross_weight').on('input', function(){
                var gross_weight = $(this).val();
                var tare_weight = $('#tare_weight').val();
                var net_weight = $('#net_weight').val();
                if (gross_weight == "")
                {
                    $('#net_weight').val('');
                }
                else
                {
                    $('#net_weight').val(gross_weight - tare_weight);
                }
            });
        });

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

            dropdown.addEventListener('keyup', function(e) {
                if (e.key === 'Insert' || (e.key.toLowerCase() === 'i' && e.ctrlKey)) {
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
            dropDownSelector: '#place_id',
            modalSelector: '#placeModal',
            formSelector: '#placeForm',
            fields: {
                id : '#place_id',
                name: '#place_name',
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
            dropDownSelector: '#royalty_id',
            modalSelector: '#royaltyModal',
            formSelector: '#royaltyForm',
            fields: {
                id : '#royalty_id',
                name: '#royalty_name',
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
            dropDownSelector: '#party_id',
            modalSelector: '#partyModal',
            formSelector: '#partyForm',
            fields: {
                id : '#party_id',
                name: '#party_name',
            },
            labelField: 'name',
        })
    </script>
    
@endpush