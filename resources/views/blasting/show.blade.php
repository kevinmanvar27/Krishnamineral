@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">
            <div class="page-content">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Blasting Details</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('blasting.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Blaster Name</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->blasterName->b_name ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Date Time</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->date_time ? \Carbon\Carbon::parse($blasting->date_time)->format('d-m-Y h:i A') : 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Notes</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->b_notes ?? 'N/A' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Geliten</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->geliten ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Geliten Rate</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->geliten_rate ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Geliten Total</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->geliten_total ?? 'N/A' }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">DF</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->df ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">DF Rate</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->df_rate ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">DF Total</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->df_total ?? 'N/A' }}</a></div>
                            </div>
                            <hr class="mt-0">                            
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">ODVAT</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->odvat ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">OD Rate</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->od_rate ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">OD Total</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->od_total ?? 'N/A' }}</a></div>
                            </div>
                            <hr class="mt-0">   
                            @if(!empty($blasting->controll))
                                <div class="row mb-4">
                                    <div class="col-lg-4 fw-medium">Control Details</div>
                                    <div class="col-lg-8">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Meter</th>
                                                    <th>Rate</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($blasting->controll as $control)
                                                    <tr>
                                                        <td>{{ $control['meter'] ?? 'N/A' }}</td>
                                                        <td>{{ $control['rate'] ?? 'N/A' }}</td>
                                                        <td>{{ $control['total'] ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <hr class="mt-0">   
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Gross Total</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $blasting->gross_total ?? 'N/A' }}</a></div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
@endpush