@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">
            <div class="page-content">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Show Drilling Name</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('drilling-name.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Drilling Name</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $drillingName->d_name ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Contact Number</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $drillingName->phone_no ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Drilling Status</div>
                                <div class="col-lg-8">
                                    <a href="javascript:void(0);" 
                                       class="badge bg-{{ $drillingName->status == 'active' ? 'success' : 'danger' }}">
                                        {{ $drillingName->status ?? '-' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@push('scripts')
@endpush