@extends('layouts.app')

@section('content')

<div class="wrapper">
    <div class="page-wrapper">   
        <div class="page-content">
            <div class="row">
                <div class="row mb-3">
                    <!-- [Leads] start -->
                    <div class="col-xxl-12">
                        @if(session('auto_download_pdf') && session('pdf_sales_id'))
                            <script>
                                window.addEventListener('DOMContentLoaded', function () {
                                    const pdfUrl = "{{ route('sales.sales-pdf', session('pdf_sales_id')) }}";
                                    const a = document.createElement('a');
                                    a.href = pdfUrl;
                                    a.download = 'Sales.pdf';
                                    document.body.appendChild(a);
                                    a.click();
                                    document.body.removeChild(a);
                                });
                            </script>
                            @php
                                session()->forget(['auto_download_pdf', 'pdf_sales_id']);
                            @endphp
                        @endif
                        @session('success')
                            <div class="alert alert-success" role="alert"> 
                                {{ $value }}
                            </div>
                        @endsession
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Sales Edit</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="border-b">
                                                <th>Date Time</th>
                                                <th>Challan Numbber</th>
                                                <th>Vehicle Number</th>
                                                <th>Transporter Name</th>
                                                <th>Contact NO</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $activeSales = $sales->where('status', '1');
                                            @endphp
                                            @forelse ($activeSales as $sale)
                                                <tr>
                                                    <td>{{ $sale->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                    <td>{{ "S_".$sale->id }}</td>
                                                    <td>{{ $sale->vehicle ? $sale->vehicle->name : '' }}</td>
                                                    <td>{{ $sale->transporter }}</td>
                                                    <td>{{ $sale->contact_number }}</td>
                                                    <td class="d-flex">
                                                        <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No Record Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Leads] end -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection