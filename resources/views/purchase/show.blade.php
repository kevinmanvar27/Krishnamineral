@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">
            <div class="page-content">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Show Purchases</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('purchase.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Purchase Challan Number</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ 'S_'.$purchases->id ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Purchase Date Time</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->date_time ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Transporter</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->transporter ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Contact Number</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->contact_number ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Gross Weight</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->gross_weight ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Tare Weight</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->tare_weight ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Net Weight</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->net_weight ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Material</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->material->name ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Loading</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->loading->name ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Quarry</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->quarry->name ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Receiver</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->receiver->name ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Driver Name</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->driver->name    ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Driver Name</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->driver->driver   ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Driver Contact Number</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->driver->contact_number   ?? '-' }}</a></div>
                            </div>  
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Carting</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->carting_id == '0' ? 'Carting' : 'Self' }}</a></div>
                            </div>  
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Note</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $purchases->note ?? '' }}</a></div>
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