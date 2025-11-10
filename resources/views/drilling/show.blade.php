@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="page-wrapper">
            <div class="page-content">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profileTab" role="tabpanel">
                        <div class="card card-body lead-info">
                            <div class="mb-4 d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Drilling Details</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">
                                        <a href="{{ route('drilling.index') }}" class="btn btn-sm btn-primary">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Drilling ID</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">DR_{{ $drilling->drilling_id }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Drilling Name</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $drilling->drillingName->d_name ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Note</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $drilling->d_notes ?? 'N/A' }}</a></div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Date Time</div>
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ \Carbon\Carbon::parse($drilling->date_time)->format('d M, Y H:i:s') }}</a></div>
                            </div>
                            <hr class="mt-0">
                            <!-- Hole Details Table -->
                            @if(!empty($drilling->hole) && is_array($drilling->hole))
                            <div class="row mb-4">
                                <div class="col-lg-4 fw-medium">Hole Details</div>
                                <div class="col-lg-8">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Hole</th>
                                                <th>FOOT</th>
                                                <th>RATE</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($drilling->hole as $holeItem)
                                                <tr>
                                                    <td>{{ $holeItem['name'] ?? 'N/A' }}</td>
                                                    <td>{{ $holeItem['foot'] ?? 'N/A' }}</td>
                                                    <td>{{ $holeItem['rate'] ?? 'N/A' }}</td>
                                                    <td>{{ $holeItem['total'] ?? 'N/A' }}</td>
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
                                <div class="col-lg-8"><a href="javascript:void(0);">{{ $drilling->gross_total }}</a></div>
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