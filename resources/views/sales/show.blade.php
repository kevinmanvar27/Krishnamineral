@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">
            <div class="page-content">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Show Sales</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('sales.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Sales Challan Number</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ 'S_'.$sales->id ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Sales Date Time</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->date_time ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Transporter</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->transporter ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Contact Number</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->contact_number ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Gross Weight</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->gross_weight ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Tare Weight</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->tare_weight ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Net Weight</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->net_weight ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Party Weight</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->party_weight ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Material</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->material->name ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Loading</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->loading->name ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Place</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->place->name ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Party</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->party->name ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Royalty</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->royalty->name ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Royalty Number</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->royalty_number    ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Royalty Tone</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->royalty_tone    ?? '-' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Driver Name</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->driver->name    ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Driver Name</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->driver->driver   ?? '-' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Driver Contact Number</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->driver->contact_number   ?? '-' }}</a></div>
                            </div>  
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Carting</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->carting_id == '0' ? 'Carting' : 'Self' }}</a></div>
                            </div>  
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Note</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $sales->note ?? '-' }}</a></div>
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