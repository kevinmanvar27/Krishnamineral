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
                                <h5 class="card-title">Drilling Name</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('drilling-name.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ isset($drillingName) ? route('drilling-name.update', $drillingName->dri_id) : route('drilling-name.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($drillingName))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">    
                                    <div class="row align-items-center d-flex justify-content-between">
                                        <div class="col-lg-6 mb-4">
                                            <label for="d_name" class="form-label mb-2">Drilling Name</label>
                                            <div class="input-group">
                                                <input type="text" name="d_name" placeholder="Enter Drilling Name" class="form-control"  id="d_name"  value="{{ old('d_name', $drillingName->d_name ?? '') }}">
                                            </div>
                                            @error('d_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="phone_no" class="form-label mb-2">Phone Number</label>
                                            <div class="input-group">
                                                <input type="number" name="phone_no" placeholder="Enter Phone Number" class="form-control"  id="phone_no" maxlength="10" minlength="10" value="{{ old('phone_no', $drillingName->phone_no ?? '') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                                            </div>
                                            @error('phone_no')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 mb-4">
                                            <label for="status" class="form-label mb-2">Drilling Status</label>
                                            <div class="input-group">
                                                <select name="status" id="status" class="form-control">
                                                    <option value="">Select Status</option>
                                                    <option value="active" {{ old('status', ($drillingName->status ?? '') == 'active' ? 'active' : '') == 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ old('status', ($drillingName->status ?? '') == 'inactive' ? 'inactive' : '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
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