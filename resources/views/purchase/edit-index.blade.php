@extends('layouts.app')

@section('content')

<div class="wrapper">
    <div class="page-wrapper">   
        <div class="page-content">
            <div class="row">
                <div class="row mb-3">
                    @php
                        $i = ($purchases->currentPage() - 1) * $purchases->perPage();
                    @endphp
                    <!-- [Leads] start -->
                    <div class="col-xxl-12">
                        @if(session('auto_download_pdf') && session('pdf_purchase_id'))
                            <script>
                                window.addEventListener('DOMContentLoaded', function () {
                                    const pdfUrl = "{{ route('purchase.purchase-pdf', session('pdf_purchase_id')) }}";
                                    window.open(pdfUrl, '_blank');
                                });
                            </script>
                            @php
                                session()->forget(['auto_download_pdf', 'pdf_purchase_id']);
                            @endphp
                        @endif
                        @session('success')
                            <div class="alert alert-success" role="alert"> 
                                {{ $value }}
                            </div>
                        @endsession
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title">Sales Edit</h5>
                                <div class="d-flex gap-2">
                                    <input type="date" id="searchDateFrom" class="form-control" placeholder="From Date">
                                    <input type="date" id="searchDateTo" class="form-control" placeholder="To Date">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="border-b">
                                                <th>Challan Numbber</th>
                                                <th>Vehicle Number</th>
                                                <th>Transporter Name</th>
                                                <th>Contact NO</th>
                                                <th>Date Time</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <select id="searchChallan" class="form-select js-select2">
                                                        <option value="">All Challans</option>
                                                        @foreach($allChallans->unique() as $challan)
                                                            <option value="{{ $challan }}" {{ old('challan') == $challan ? 'selected' : '' }}>{{ 'P_'.$challan }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchVehicle" class="form-select js-select2">
                                                        <option value="">All Vehicles</option>
                                                        @foreach($allVehicles as $vehicle)
                                                            <option value="{{ $vehicle->id }}" {{ old('vehicle') == $vehicle->id ? 'selected' : '' }}>
                                                                {{ $vehicle->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchTransporter" class="form-select js-select2">
                                                        <option value="">All Transporters</option>
                                                        @foreach($allTransporters as $name)
                                                            <option value="{{ $name }}" {{ old('name') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchContact" class="form-select js-select2">
                                                        <option value="">All Contact Numbers</option>
                                                        @foreach($allContacts->unique() as $contact)
                                                            <option value="{{ $contact }}" {{ old('contact_number') == $contact ? 'selected' : '' }}>{{ $contact }}</option>
                                                            @endforeach
                                                    </select>
                                                </th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableData">
                                            @php $i = $i ?? 0; @endphp
                                            @forelse ($purchases as $purchase)
                                                <tr>
                                                    <td>{{ "P_".$purchase->id }}</td>
                                                    <td>{{ $purchase->vehicle ? $purchase->vehicle->name : '' }}</td>
                                                    <td>{{ $purchase->transporter }}</td>
                                                    <td>{{ $purchase->contact_number }}</td>
                                                    <td>{{ $purchase->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                    <td class="d-flex">
                                                        <a href="{{ route('purchase.edit', $purchase->id) }}" class="btn btn-primary btn-sm">Edit</a>
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
                        <div class="card-footer">
                            <div class="d-flex justify-content-start" id="paginationLinks">
                                {!! $purchases->links() !!}
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

@push('scripts')

    <script>
        $(document).ready(function () {

            function fetch_data(page = 1) {
                $.ajax({
                    url: "{{ route('purchase.editIndex') }}",
                    type: "GET",
                    data: {
                        page: page,
                        transporter: $('#searchTransporter').val(),
                        contact_number: $('#searchContact').val(),
                        challan: $('#searchChallan').val(),
                        vehicle: $('#searchVehicle').val(),
                        date_from: $('#searchDateFrom').val(),
                        date_to: $('#searchDateTo').val()
                    },
                    success: function (response) {
                        let newBody = $(response).find('#tableData').html();
                        $('#tableData').html(newBody);

                        let newPagination = $(response).find('#paginationLinks').html();
                        $('#paginationLinks').html(newPagination);
                    }
                });
            }

            $('#searchTransporter, #searchDateFrom, #searchDateTo, #searchContact, #searchChallan, #searchVehicle').on('change', function () {
                fetch_data();
            });

            $(document).on('click', '#paginationLinks .pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });
        });
    </script>

@endpush