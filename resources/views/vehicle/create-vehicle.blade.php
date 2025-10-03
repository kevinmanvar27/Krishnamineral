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
                                <h5 class="card-title">Vehicle</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ isset($vehicle) ? route('vehicles.editIndex') : route('vehicle.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($vehicle) ? route('vehicle.update', $vehicle) : route('vehicle.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if(isset($vehicle))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">
                                    <div class="row align-items-center d-flex justify-content-between">
                                        <div class="col-lg-12 mb-4">
                                            <label for="name" class="form-label mb-2">Vehicle Number</label>
                                            <div class="input-group">
                                                <input type="text" name="name" placeholder="Enter Vehicle Number" class="form-control"  id="name"  value="{{ old('name', $vehicle->name ?? '') }}">
                                            </div>
                                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-12 mb-4">
                                            <label for="vehicle_name" class="form-label mb-2">Transporter Name</label>
                                            <div class="input-group">
                                                <input type="text" name="vehicle_name" placeholder="Enter Transporter Name" class="form-control"  id="vehicle_name" value="{{ old('vehicle_name', $vehicle->vehicle_name ?? '') }}">
                                            </div>
                                            @error('vehicle_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>  
                                    </div>
                                    <div class="row align-items-center">  
                                        <div class="col-lg-12 mb-4">
                                            <label for="contact_number" class="form-label mb-2">Contact Number</label>
                                            <div class="input-group">
                                                <input type="number" name="contact_number" placeholder="Enter Contact Number" class="form-control" id="contact_number"value="{{ old('contact_number', $vehicle->contact_number ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row justify-content-between">
                                        <div class="col-lg-3">
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
            <!-- [ Main Content ] end -->
        </div>
    </main>
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
        
    </script>
    
@endpush