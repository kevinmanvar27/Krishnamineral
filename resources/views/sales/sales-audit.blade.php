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
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title">Sales Audit</h5>
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
                                                <th>Challan Number</th>
                                                <th>Party</th>
                                                <th>Net Weight</th>
                                                <th>Party Weight</th>
                                                <th>Material</th>
                                                <th>Date & Time</th>
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
                                                    <select id="searchParty" class="form-select js-select2">
                                                        <option value="">All Parties</option>
                                                        @foreach($allParties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th></th>
                                                <th></th>
                                                <th>
                                                    <select id="searchMaterial" class="form-select js-select2">
                                                        <option value="">All Materials</option>
                                                        @foreach($allMaterials as $material)
                                                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th></th>
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
                                                    <td>{{ $sale->party->name ?? '-' }}</td>
                                                    <td>{{ $sale->net_weight ?? '-' }}</td>
                                                    <td>
                                                        <input type="number" class="form-control party-weight-input" 
                                                               value="{{ $sale->party_weight ?? '' }}" 
                                                               placeholder="Enter party weight" min="0">
                                                    </td>
                                                    <td>{{ $sale->material->name ?? '-' }}</td>
                                                    <td>{{ $sale->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                    <td>
                                                        <button class="btn btn-success btn-sm update-party-weight">OK</button>
                                                        <a href="javascript:void(0)" class="btn btn-info btn-sm text-white challan-link" data-sale-id="{{ $sale->id }}">
                                                            <i class="lni lni-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No Record Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
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
                url: "{{ route('sales.salesAudit') }}",
                type: "GET",
                data: {
                    page: page,
                    date_from: $('#searchDateFrom').val(),
                    date_to: $('#searchDateTo').val(),
                    challan: $('#searchChallan').val(),
                    party: $('#searchParty').val(),
                    material: $('#searchMaterial').val()
                },
                success: function (response) {
                    let newBody = $(response).find('#tableData').html();
                    $('#tableData').html(newBody);

                    let newPagination = $(response).find('#paginationLinks').html();
                    $('#paginationLinks').html(newPagination);
                }
            });
        }

        // Apply filters when date inputs change
        $('#searchDateFrom, #searchDateTo, #searchChallan, #searchParty, #searchMaterial').on('change', function () {
            fetch_data();
        });

        // Handle pagination clicks
        $(document).on('click', '#paginationLinks .pagination a', function (e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            fetch_data(page);
        });

        // Handle update party weight button click
        $(document).on('click', '.update-party-weight', function () {
            let row = $(this).closest('tr');
            let id = row.data('id');
            let partyWeight = row.find('.party-weight-input').val();
            let netWeight = row.find('td:eq(3)').text(); // Get net weight from the 4th column (0-indexed)

            // If party weight is empty, use net weight
            if (partyWeight === '') {
                partyWeight = netWeight;
            }

            // Validate that partyWeight is a number
            if (isNaN(partyWeight) || partyWeight === '' || parseFloat(partyWeight) < 0) {
                alert('Please enter a valid party weight or ensure net weight is available');
                return;
            }

            $.ajax({
                url: "{{ url('sales') }}/" + id + "/update-party-weight",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    party_weight: partyWeight
                },
                success: function (response) {
                    if (response.success) {
                        // Remove the row from the table since status is now 1
                        // alert('Party weight updated successfully');
                        toastr.success('Sales records updated successfully');
                        row.remove();
                    } else {
                        alert('Error updating party weight: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error updating party weight');
                }
            });
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
    });
</script>
@endpush