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
                                <h5 class="card-title">Sales Rate</h5>
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
                                    .sales-rate-table {
                                        width: 100%;
                                        table-layout: auto;
                                        min-width: 1200px;
                                    }
                                    .sales-rate-table th, .sales-rate-table td {
                                        white-space: nowrap;
                                        padding: 0.5rem;
                                    }
                                    .sales-rate-table input.form-control {
                                        width: 100px;
                                        min-width: 100px;
                                    }
                                </style>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered sales-rate-table">
                                        <thead>
                                            <tr class="border-b">
                                                <th>Challan Number</th>
                                                <th>Vehicle Number</th>
                                                <th>Net Weight</th>
                                                <th>Party Weight</th>
                                                <th>Material</th>
                                                <th>Place</th>
                                                <th>Party</th>
                                                <th>Royalty</th>
                                                <th>Royalty Number</th>
                                                <th>Rate</th>
                                                <th>GST</th>
                                                <th>Amount</th>
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
                                                    <select id="searchNetWeight" class="form-select js-select2">
                                                        <option value="">All Net Weights</option>
                                                        @foreach($allNetWeights as $netWeight)
                                                            <option value="{{ $netWeight }}">{{ $netWeight }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchPartyWeight" class="form-select js-select2">
                                                        <option value="">All Party Weights</option>
                                                        @foreach($allPartyWeights as $partyWeight)
                                                            <option value="{{ $partyWeight }}">{{ $partyWeight }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchMaterial" class="form-select js-select2">
                                                        <option value="">All Materials</option>
                                                        @foreach($allMaterials as $material)
                                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
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
                                                <th>
                                                    <select id="searchRoyalty" class="form-select js-select2">
                                                        <option value="">All Royalties</option>
                                                        @foreach($allRoyalties as $royalty)
                                                            <option value="{{ $royalty->id }}">{{ $royalty->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th></th>
                                                <th>
                                                    <input type="number" id="searchRate" class="form-control" min="0" placeholder="Rate">
                                                </th>
                                                <th>
                                                    <input type="number" id="searchGst" class="form-control" min="0" placeholder="GST">
                                                </th>
                                                <th></th>
                                                <th>
                                                    <select id="searchCarting" class="form-select" disabled style="display: none;">
                                                        <option value="0" selected>Carting</option>
                                                    </select>
                                                </th>
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
                                                    <td>{{ $sale->net_weight ?? '-' }}</td>
                                                    <td>{{ $sale->party_weight ?? '-' }}</td>
                                                    <td>{{ $sale->material->name ?? '-' }}</td>
                                                    <td>{{ $sale->place->name ?? '-' }}</td>
                                                    <td>{{ $sale->party->name ?? '-' }}</td>
                                                    <td>{{ $sale->royalty->name ?? '-' }}</td>
                                                    <td>{{ $sale->royalty_number ?? '-' }}</td>
                                                    <td>
                                                        <input type="number" class="form-control rate-input" 
                                                               data-sale-id="{{ $sale->id }}" 
                                                               value="{{ $sale->rate ?? '' }}" 
                                                               min="0"
                                                               style="width: 100px; min-width: 100px;">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control gst-input" 
                                                               data-sale-id="{{ $sale->id }}" 
                                                               value="{{ $sale->gst ?? '' }}" 
                                                               min="0"
                                                               style="width: 100px; min-width: 100px;">
                                                    </td>
                                                    <td>{{ $sale->amount ?? '-' }}</td>
                                                    <td>Carting</td>
                                                    <td>
                                                        <a href="javascript:void(0)" class="challan-link btn btn-info btn-sm text-white" data-sale-id="{{ $sale->id }}">
                                                            <i class="lni lni-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="13" class="text-center">No Record Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="9" class="text-end"><strong>Update Selected Records:</strong></td>
                                                <td>
                                                    <input type="number" id="bulkRate" class="form-control" min="0" placeholder="Rate" style="width: 100px; min-width: 100px;">
                                                </td>
                                                <td>
                                                    <input type="number" id="bulkGst" class="form-control" min="0" placeholder="GST" style="width: 100px; min-width: 100px;">
                                                </td>
                                                <td colspan="2">
                                                    <button id="updateBulk" class="btn btn-primary">Update</button>
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
                url: "{{ route('sales.rate') }}",
                type: "GET",
                data: {
                    page: page,
                    date_from: $('#searchDateFrom').val(),
                    date_to: $('#searchDateTo').val(),
                    challan: $('#searchChallan').val(),
                    vehicle: $('#searchVehicle').val(),
                    net_weight: $('#searchNetWeight').val(),
                    party_weight: $('#searchPartyWeight').val(),
                    material: $('#searchMaterial').val(),
                    place: $('#searchPlace').val(),
                    party: $('#searchParty').val(),
                    royalty: $('#searchRoyalty').val(),
                    rate: $('#searchRate').val(),
                    gst: $('#searchGst').val()
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
        $('#searchDateFrom, #searchDateTo, #searchChallan, #searchVehicle, #searchNetWeight, #searchPartyWeight, #searchMaterial, #searchPlace, #searchParty, #searchRoyalty, #searchRate, #searchGst').on('change keyup', function () {
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
            let row = $(this).closest('tr');
            let rate = $(this).val();
            let gst = row.find('.gst-input').val();
            
            // Update the single record
            updateSalesRecord(saleId, rate, gst, row);
        });
        
        // Handle individual gst input changes
        $(document).on('change', '.gst-input', function () {
            let saleId = $(this).data('sale-id');
            let row = $(this).closest('tr');
            let rate = row.find('.rate-input').val();
            let gst = $(this).val();
            
            // Update the single record
            updateSalesRecord(saleId, rate, gst, row);
        });

        // Handle bulk update button
        $('#updateBulk').on('click', function () {
            let bulkRate = $('#bulkRate').val();
            let bulkGst = $('#bulkGst').val();
            
            if (bulkRate === '' && bulkGst === '') {
                alert('Please enter at least one value (Rate or GST)');
                return;
            }
            
            // Get all visible sales records
            let salesToUpdate = [];
            $('#tableData tr').each(function () {
                let saleId = $(this).data('id');
                if (saleId) {
                    salesToUpdate.push({
                        id: saleId,
                        rate: bulkRate !== '' ? bulkRate : null,
                        gst: bulkGst !== '' ? bulkGst : null
                    });
                }
            });
            
            if (salesToUpdate.length > 0) {
                updateBulkSales(salesToUpdate);
            }
        });

        // Function to update a single sales record
        function updateSalesRecord(saleId, rate, gst, row) {
            $.ajax({
                url: "{{ route('sales.updateRate', ['id' => '__ID__']) }}".replace('__ID__', saleId),
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    rate: rate,
                    gst: gst
                },
                success: function (response) {
                    if (response.success) {
                        // Show success message
                        toastr.success('Sales record updated successfully');
                        // Update the amount field in the row
                        if (response.amount !== undefined) {
                            row.find('td:eq(11)').text(parseFloat(response.amount).toFixed(2)); // Update amount column
                        }
                    } else {
                        alert('Error updating sales record: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error updating sales record');
                }
            });
        }

        // Function to update multiple sales records
        function updateBulkSales(salesData) {
            $.ajax({
                url: "{{ route('sales.bulkUpdateRate') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    sales: salesData
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success('Sales records updated successfully');
                        // Refresh the table
                        fetch_data();
                    } else {
                        alert('Error updating sales records: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error updating sales records');
                }
            });
        }
    });
</script>
@endpush