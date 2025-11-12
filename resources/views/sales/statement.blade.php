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
                                <h5 class="card-title">Sales Statement</h5>
                                <div class="d-flex gap-2">
                                    <input type="date" id="searchDateFrom" class="form-control" placeholder="From Date">
                                    <input type="date" id="searchDateTo" class="form-control" placeholder="To Date">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6 d-flex gap-2 mb-3">
                                        <button type="button" id="resetFilters" class="btn btn-secondary">Reset</button>
                                        <button type="button" id="printStatement" class="btn btn-primary">Print</button>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="searchPartyName" class="form-select js-select2">
                                            <option value="">All Parties</option>
                                            @foreach($allParties as $party)
                                                <option value="{{ $party->name }}">{{ $party->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="searchMaterialName" class="form-select js-select2">
                                            <option value="">All Materials</option>
                                            @foreach($allMaterials as $material)
                                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    @foreach($partyWiseSales as $partyData)
                                    <div class="mb-4 party-section">
                                        <h4 class="mb-3 party-header">Party: {{ $partyData['party']->name ?? 'Under Pending Load' }}</h4>
                                        
                                        <table class="table table-striped table-bordered party-table" style="width:100%">
                                            <thead>
                                                <tr class="border-b">
                                                    <th>Challan</th>
                                                    <th>Date</th>
                                                    <th>Vehicle Number</th>
                                                    <th>Royalty Name</th>
                                                    <th>Royalty Number</th>
                                                    <th>Place</th>
                                                    <th>Material</th>
                                                    <th>Net Weight</th>
                                                    <th>Rate</th>
                                                    <th>GST(%)</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $partyNetWeight = 0;
                                                    $partyPartyWeight = 0;
                                                    $partyAmount = 0;
                                                @endphp
                                                @foreach($partyData['challanWiseData'] as $challanId => $challanData)
                                                    @forelse ($challanData['records'] as $sale)
                                                    <tr data-id="{{ $sale->id }}">
                                                        <td>{{ $challanData['challanNumber'] }}</td>
                                                        <td>{{ $sale->created_at->timezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                                                        <td>{{ $sale->vehicle->name ?? '-' }}</td>
                                                        <td>{{ $sale->royalty->name ?? '-' }}</td>
                                                        <td>{{ $sale->royalty_number ?? '-' }}</td>
                                                        <td>{{ $sale->place->name ?? '-' }}</td>
                                                        <td>{{ $sale->material->name ?? '-' }}</td>
                                                        @php
                                                            // Calculate display weight: party weight if available and not zero, otherwise net weight
                                                            $displayWeight = (!is_null($sale->party_weight) && $sale->party_weight != 0) ? $sale->party_weight : ($sale->net_weight ?? 0);
                                                            $partyNetWeight += $displayWeight;
                                                            $partyAmount += $sale->amount ?? 0;
                                                        @endphp
                                                        <td class="text-end">{{ number_format($displayWeight, 2) }}</td>
                                                        <td class="text-end">{{ number_format($sale->rate ?? 0, 2) }}</td>
                                                        <td class="text-end">{{ number_format($sale->gst ?? 0, 2) }}</td>
                                                        <td class="text-end">{{ number_format($sale->amount ?? 0, 2) }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="11" class="text-center">No Record Found</td>
                                                    </tr>
                                                    @endforelse
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-secondary">
                                                    <th colspan="7" class="text-end">Party Total:</th>
                                                    <th class="text-end">{{ number_format($partyNetWeight, 2) }}</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th class="text-end">{{ number_format($partyAmount, 2) }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @endforeach
                                    
                                    <!-- @if(count($partyWiseSales) > 0)
                                    <div class="mt-4 grand-total-section">
                                        <table class="table table-bordered grand-total-table">
                                            <tfoot>
                                                <tr class="table-success">
                                                    <th colspan="7" class="text-end grand-total-label">Grand Total:</th>
                                                    <th class="text-end grand-total-value">{{ number_format($grandTotalDisplayWeight, 2) }}</th>
                                                    <th class="text-end grand-total-value">{{ number_format($grandTotalAmount, 2) }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @endif -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} entries
                                </div>
                                <div class="d-flex justify-content-start" id="paginationLinks">
                                    {!! $sales->links() !!}
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

@push('scripts')
<script>
    $(document).ready(function () {
        let fetchTimer = null;

        // Function to fetch data with filters
        function fetch_data(page = 1) {
            const dateFrom = $('#searchDateFrom').val();
            const dateTo = $('#searchDateTo').val();
            const partyName = $('#searchPartyName').val();
            const materialName = $('#searchMaterialName').val();
            
            console.log('Fetching data with filters:', {
                page: page,
                date_from: dateFrom,
                date_to: dateTo,
                party_name: partyName,
                material_name: materialName
            });
            
            // Show loading indicator
            $('.table-responsive').html('<div class="text-center py-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            $.ajax({
                url: "{{ route('sales.statement') }}",
                type: "GET",
                data: {
                    page: page,
                    date_from: dateFrom,
                    date_to: dateTo,
                    party_name: partyName,
                    material_name: materialName
                },
                success: function (response) {
                    console.log('AJAX response received');
                    try {
                        // Parse the response as HTML
                        let $response = $(response);
                        
                        // Replace only the table-responsive content (the main table data)
                        let newTableContent = $response.find('.table-responsive').html();
                        $('.table-responsive').html(newTableContent);

                        // Update pagination
                        let newPagination = $response.find('#paginationLinks').html();
                        $('#paginationLinks').html(newPagination);
                        
                        // Update the footer information
                        let newFooter = $response.find('.card-footer').html();
                        $('.card-footer').html(newFooter);
                        
                        // Reinitialize select2 for the new dropdowns
                        $('.js-select2').select2({
                            theme: 'bootstrap-5',
                            width: '100%'
                        });
                    } catch (e) {
                        console.log('Error parsing AJAX response:', e);
                        $('.table-responsive').html('<div class="text-center py-5 text-danger">Error loading data. Please try again.</div>');
                    }
                },
                error: function (xhr, status, error) {
                    console.log('AJAX Error: ' + error);
                    console.log(xhr.responseText);
                    $('.table-responsive').html('<div class="text-center py-5 text-danger">Error loading data. Please try again.</div>');
                }
            });
        }

        // Print statement function
        function print_statement() {
            // Get current filter values
            const dateFrom = $('#searchDateFrom').val();
            const dateTo = $('#searchDateTo').val();
            const partyName = $('#searchPartyName').val();
            const materialName = $('#searchMaterialName').val();
            
            // Build URL with parameters
            let url = "{{ route('sales.statement.print') }}?";
            let params = [];
            
            if (dateFrom) params.push("date_from=" + encodeURIComponent(dateFrom));
            if (dateTo) params.push("date_to=" + encodeURIComponent(dateTo));
            if (partyName) params.push("party_name=" + encodeURIComponent(partyName));
            if (materialName) params.push("material_name=" + encodeURIComponent(materialName));
            
            url += params.join("&");
            
            // Open print window
            window.open(url, '_blank');
        }

        // Print individual challan function
        function print_challan(challanId) {
            // Get current filter values
            const dateFrom = $('#searchDateFrom').val();
            const dateTo = $('#searchDateTo').val();
            const partyName = $('#searchPartyName').val();
            const materialName = $('#searchMaterialName').val();
            
            // Build URL with parameters
            let url = "{{ route('sales.statement.print') }}?";
            let params = [];
            
            if (dateFrom) params.push("date_from=" + encodeURIComponent(dateFrom));
            if (dateTo) params.push("date_to=" + encodeURIComponent(dateTo));
            if (partyName) params.push("party_name=" + encodeURIComponent(partyName));
            if (materialName) params.push("material_name=" + encodeURIComponent(materialName));
            params.push("challan_id=" + encodeURIComponent(challanId));
            
            url += params.join("&");
            
            // Open print window
            window.open(url, '_blank');
        }

        // Apply filters with debounce to avoid excessive requests
        function applyFilters() {
            console.log('Applying filters');
            // Clear previous timer
            if (fetchTimer) {
                clearTimeout(fetchTimer);
            }
            
            // Set new timer to fetch data after 500ms of inactivity
            fetchTimer = setTimeout(function() {
                console.log('Debounce timer expired, fetching data');
                fetch_data();
            }, 500);
        }

        // Apply filters when any input changes
        $('#searchDateFrom, #searchDateTo').on('change', function () {
            console.log('Date filter changed:', $(this).attr('id'), 'Value:', $(this).val());
            // Log all current filter values
            console.log('Current filter values:', {
                date_from: $('#searchDateFrom').val(),
                date_to: $('#searchDateTo').val(),
                party_name: $('#searchPartyName').val(),
                material_name: $('#searchMaterialName').val()
            });
            applyFilters();
        });
        
        // Apply filters when Select2 inputs change
        $('#searchPartyName').on('select2:select change', function () {
            console.log('Select2 filter changed:', $(this).attr('id'), 'Value:', $(this).val());
            // Log all current filter values
            console.log('Current filter values:', {
                date_from: $('#searchDateFrom').val(),
                date_to: $('#searchDateTo').val(),
                party_name: $('#searchPartyName').val(),
                material_name: $('#searchMaterialName').val()
            });
            applyFilters();
        });
        
        // Apply filters when material dropdown changes
        $('#searchMaterialName').on('select2:select change', function () {
            console.log('Select2 filter changed:', $(this).attr('id'), 'Value:', $(this).val());
            // Log all current filter values
            console.log('Current filter values:', {
                date_from: $('#searchDateFrom').val(),
                date_to: $('#searchDateTo').val(),
                party_name: $('#searchPartyName').val(),
                material_name: $('#searchMaterialName').val()
            });
            applyFilters();
        });
        
        // Handle pagination clicks
        $(document).on('click', '#paginationLinks .pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });
        
        // Reset filters
        $('#resetFilters').on('click', function () {
            console.log('Reset filters clicked');
            $('#searchDateFrom').val('');
            $('#searchDateTo').val('');
            $('#searchPartyName').val('').trigger('change');
            $('#searchMaterialName').val('').trigger('change');
            // Log all current filter values after reset
            console.log('Filter values after reset:', {
                date_from: $('#searchDateFrom').val(),
                date_to: $('#searchDateTo').val(),
                party_name: $('#searchPartyName').val(),
                material_name: $('#searchMaterialName').val()
            });
        });
        
        // Print statement
        $('#printStatement').on('click', function () {
            console.log('Print statement clicked');
            print_statement();
        });
        
        // Print individual challan
        $(document).on('click', '.print-challan', function () {
            const challanId = $(this).data('challan');
            console.log('Print challan clicked:', challanId);
            print_challan(challanId);
        });
        
        // Initialize select2
        $('.js-select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    });
</script>
@endpush

<style>
.party-header {
    background-color: #f8f9fa;
    padding: 10px 15px;
    border-left: 4px solid #007bff;
    font-weight: bold;
}
.party-table {
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}
.party-total-table,
.grand-total-table {
    box-shadow: 0 0 8px rgba(0,0,0,0.15);
}
.grand-total-label {
    font-size: 1.1em;
    font-weight: bold;
}
.grand-total-value {
    font-size: 1.1em;
    font-weight: bold;
}
.table-secondary th {
    background-color: #e2e3e5;
}
.table-primary th {
    background-color: #cce7ff;
}
.table-success th {
    background-color: #d4edda;
}
</style>