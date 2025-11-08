@extends('layouts.app')

@section('content')

<div class="wrapper">
    <div class="page-wrapper">   
        <div class="page-content">
            <div class="row">
                <div class="row mb-3">
                    @php
                        $i = ($sales->currentPage() - 1) * $sales->perPage();
                    @endphp
                    <!-- [Leads] start -->
                    <div class="col-xxl-12">
                        @session('success')
                            <div class="alert alert-success" role="alert"> 
                                {{ $value }}
                            </div>
                        @endsession
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title">Carting Records</h5>
                                <div class="d-flex gap-2">
                                    <input type="date" id="searchDateFrom" class="form-control" placeholder="From Date">
                                    <input type="date" id="searchDateTo" class="form-control" placeholder="To Date">
                                </div>
                            </div>
                            <div class="card-body">
                                <style>
                                    .table-responsive {
                                        overflow-x: auto;
                                        max-width: 100%;
                                    }
                                    .carting-table {
                                        width: 100%;
                                        table-layout: auto;
                                        min-width: 1200px;
                                    }
                                    .carting-table th, .carting-table td {
                                        white-space: nowrap;
                                        padding: 0.5rem;
                                    }
                                    .carting-table input.form-control {
                                        width: 100px;
                                        min-width: 100px;
                                    }
                                </style>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered carting-table">
                                        <thead>
                                            <tr class="border-b">
                                                <th>Challan Number</th>
                                                <th>Vehicle Number</th>
                                                <th>Transporter Name</th>
                                                <th>Net Weight</th>
                                                <th>Place</th>
                                                <th>Party</th>
                                                <th>Date</th>
                                                <th>Rate</th>
                                                <th>Carting Type</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <select id="searchChallan" class="form-select js-select2">
                                                        <option value="">All Challans</option>
                                                        @foreach($allChallans as $challan)
                                                            <option value="{{ $challan }}">S_{{ $challan }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchVehicle" class="form-select js-select2">
                                                        <option value="">All Vehicles</option>
                                                        @foreach($allVehicles as $vehicle)
                                                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchTransporter" class="form-select js-select2">
                                                        <option value="">All Transporters</option>
                                                        @foreach($allTransporters as $transporter)
                                                            <option value="{{ $transporter }}">{{ $transporter }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchNetWeight" class="form-select js-select2">
                                                        <option value="">All Net Weights</option>
                                                        @foreach($allNetWeights as $netWeight)
                                                            <option value="{{ $netWeight }}">{{ $netWeight }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchPlace" class="form-select js-select2">
                                                        <option value="">All Places</option>
                                                        @foreach($allPlaces as $place)
                                                            <option value="{{ $place->id }}">{{ $place->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchParty" class="form-select js-select2">
                                                        <option value="">All Parties</option>
                                                        @foreach($allParties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th></th>
                                                <th>
                                                    <input type="number" id="searchRate" class="form-control" min="0" placeholder="Rate">
                                                </th>
                                                <th>
                                                    <select id="searchCarting" class="form-select">
                                                        <option value="">All Types</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableData">
                                            @php $i = $i ?? 0; @endphp
                                            @forelse ($sales as $sale)
                                                <tr data-id="{{ $sale->id }}">
                                                    <td>
                                                        S_{{ $sale->id }}
                                                    </td>
                                                    <td>{{ $sale->vehicle->name ?? '-' }}</td>
                                                    <td>{{ $sale->vehicle->vehicle_name ?? '-' }}</td>
                                                    <td>{{ $sale->net_weight ?? '-' }}</td>
                                                    <td>{{ $sale->place->name ?? '-' }}</td>
                                                    <td>{{ $sale->party->name ?? '-' }}</td>
                                                    <td>{{ $sale->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                    <td>
                                                        <input type="number" class="form-control rate-input" 
                                                               data-sale-id="{{ $sale->id }}" 
                                                               value="{{ $sale->carting_rate ?? '' }}" 
                                                               style="width: 100px; min-width: 100px;">
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input radio-input" type="radio" 
                                                                   name="carting_radio_{{ $sale->id }}" 
                                                                   id="radio_yes_{{ $sale->id }}" 
                                                                   value="1" 
                                                                   data-sale-id="{{ $sale->id }}"
                                                                   {{ $sale->carting_radio == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="radio_yes_{{ $sale->id }}">Yes</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input radio-input" type="radio" 
                                                                   name="carting_radio_{{ $sale->id }}" 
                                                                   id="radio_no_{{ $sale->id }}" 
                                                                   value="0" 
                                                                   data-sale-id="{{ $sale->id }}"
                                                                   {{ $sale->carting_radio == 0 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="radio_no_{{ $sale->id }}">No</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0)" class="btn btn-info btn-sm text-white challan-link" data-sale-id="{{ $sale->id }}">
                                                            <i class="lni lni-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center">No Record Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" class="text-end"><strong>Update Selected Records:</strong></td>
                                                <td>
                                                    <input type="number" id="bulkRate" class="form-control" min="0" placeholder="Rate" style="width: 100px; min-width: 100px;">
                                                </td>
                                                <td>
                                                    <select id="bulkCartingRadio" class="form-select">
                                                        <option value="">Select Type</option>
                                                        <option value="1">Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                    <button id="updateBulk" class="btn btn-primary mt-2">Update</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-start" id="paginationLinks">
                                {!! $sales->links() !!}
                            </div>
                        </div>
                    </div>
                    <!-- [Leads] end -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Challan Details Modal -->
<div class="modal fade" id="challanModal" tabindex="-1" aria-labelledby="challanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="challanModalLabel">Challan Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="challanModalBody">
                <!-- Content will be loaded here via AJAX -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Function to fetch data with filters
        function fetch_data(page = 1) {
            $.ajax({
                url: "{{ route('carting.index') }}",
                type: "GET",
                data: {
                    page: page,
                    date_from: $('#searchDateFrom').val(),
                    date_to: $('#searchDateTo').val(),
                    challan: $('#searchChallan').val(),
                    vehicle: $('#searchVehicle').val(),
                    net_weight: $('#searchNetWeight').val(),
                    transporter: $('#searchTransporter').val(),
                    place: $('#searchPlace').val(),
                    party: $('#searchParty').val(),
                    carting_rate: $('#searchRate').val(),
                    carting_radio: $('#searchCarting').val()
                },
                success: function (response) {
                    let newBody = $(response).find('#tableData').html();
                    $('#tableData').html(newBody);

                    let newPagination = $(response).find('#paginationLinks').html();
                    $('#paginationLinks').html(newPagination);
                }
            });
        }

        // Apply filters when inputs change
        $('#searchDateFrom, #searchDateTo, #searchChallan, #searchVehicle, #searchNetWeight, #searchTransporter, #searchPlace, #searchParty, #searchRate, #searchCarting').on('change keyup', function () {
            fetch_data();
        });

        // Handle pagination clicks
        $(document).on('click', '#paginationLinks .pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });

        // Handle challan link click to show modal
        $(document).on('click', '.challan-link', function () {
            let saleId = $(this).data('sale-id');
            
            // Show the modal
            $('#challanModal').modal('show');
            
            // Load the content via AJAX
            $.ajax({
                url: "{{ route('sales.showAjax', ['id' => '__ID__']) }}".replace('__ID__', saleId),
                type: "GET",
                success: function (response) {
                    // Update modal body with the response
                    $('#challanModalBody').html(response);
                },
                error: function () {
                    $('#challanModalBody').html('<div class="alert alert-danger">Error loading challan details.</div>');
                }
            });
        });

        // Handle individual rate input changes
        $(document).on('change', '.rate-input', function () {
            let saleId = $(this).data('sale-id');
            let rate = $(this).val();
            
            // Update the single record
            updateCartingRecord(saleId, rate, null);
            toastr.success('Carting rate updated successfully');
        });

        // Handle radio button changes
        $(document).on('change', '.radio-input', function () {
            let saleId = $(this).data('sale-id');
            let radioValue = $(this).val();
            
            // Update the single record
            updateCartingRecord(saleId, null, radioValue);
            toastr.success('Carting type updated successfully');
        });

        // Handle bulk update button
        $('#updateBulk').on('click', function () {
            let bulkRate = $('#bulkRate').val();
            let bulkCartingRadio = $('#bulkCartingRadio').val();
            
            if (bulkRate === '' && bulkCartingRadio === '') {
                alert('Please enter at least one value (Rate or Carting Type)');
                return;
            }
            
            // Get all visible sales records
            let salesToUpdate = [];
            $('#tableData tr').each(function () {
                let saleId = $(this).data('id');
                if (saleId) {
                    salesToUpdate.push({
                        id: saleId,
                        carting_rate: bulkRate !== '' ? bulkRate : null,
                        carting_radio: bulkCartingRadio !== '' ? bulkCartingRadio : null
                    });
                }
            });
            
            if (salesToUpdate.length > 0) {
                updateBulkCarting(salesToUpdate);
            }
        });

        // Function to update a single carting record
        function updateCartingRecord(saleId, rate, radio) {
            $.ajax({
                url: "{{ route('carting.updateCarting', ['id' => '__ID__']) }}".replace('__ID__', saleId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    carting_rate: rate,
                    carting_radio: radio
                },
                success: function (response) {
                    if (response.success) {
                        // Optionally show success message
                        console.log('Carting record updated successfully');
                        toastr.success('Carting record updated successfully');
                    } else {
                        alert('Error updating carting record: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error updating carting record');
                }
            });
        }

        // Function to update multiple carting records
        function updateBulkCarting(salesData) {
            $.ajax({
                url: "{{ route('carting.bulkUpdateCarting') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    sales: salesData
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Carting records updated successfully');
                        // Refresh the table
                        fetch_data();
                    } else {
                        alert('Error updating carting records: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error updating carting records');
                }
            });
        }
    });
</script>
@endpush