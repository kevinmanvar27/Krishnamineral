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
                                <h5 class="card-title">Driver</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ isset($drivers) ? route('driver.editIndex') : route('driver.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ isset($drivers) ? route('driver.update', $drivers->id) : route('driver.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($drivers))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">    
                                    <div class="row align-items-center d-flex justify-content-between">
                                        <div class="col-lg-6 mb-4">
                                            <label for="name" class="form-label mb-2">Name</label>
                                            <div class="input-group">
                                                <input type="text" name="name" placeholder="Enter Driver" class="form-control"  id="name"  value="{{ old('name', $drivers->name ?? '') }}">
                                            </div>
                                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="name" class="form-label mb-2">Driver Type</label>
                                            <div class="input-group">
                                                <select name="driver" id="driver" class="form-control">
                                                    <option value="">Select Driver Type</option>
                                                    <option value="self" {{ old('driver', $drivers->driver ?? '') == 'self' ? 'selected' : '' }}>Self</option>
                                                    <option value="other" {{ old('driver', $drivers->driver ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                            </div>
                                            @error('driver')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center d-flex justify-content-between">
                                        <div class="col-lg-6 mb-4">
                                            <label for="contact_number" class="form-label mb-2">Contact NO</label>
                                            <div class="input-group">
                                                <input type="number" name="contact_number" placeholder="Enter Contact Number" class="form-control"  id="contact_number" maxlength="10" minlength="10" value="{{ old('contact_number', $drivers->contact_number ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
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
