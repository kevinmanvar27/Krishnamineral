@extends('layouts.app')

@section('content')
    @if(session('auto_download_pdf') && session('pdf_drilling_id'))
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                const pdfUrl = "{{ route('drilling.drilling-pdf', session('pdf_drilling_id')) }}";
                const a = document.createElement('a');
                a.href = pdfUrl;
                a.download = 'Drilling.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        </script>
        @php
            session()->forget(['auto_download_pdf', 'pdf_drilling_id']);
        @endphp
    @endif
    <div class="wrapper">
        <div class="page-wrapper">   
            <div class="page-content">
                <div class="row">
                    <div class="row mb-3">
                        @php
                            $i = ($drillings->currentPage() - 1) * $drillings->perPage();
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
                                    <h5 class="card-title mb-0">Edit Drilling</h5>
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
                                                    <th>Drilling Name</th>
                                                    <th>Rate</th>
                                                    <th>Hole</th>
                                                    <th>Foot</th>
                                                    <th>Total</th>
                                                    <th>Gross Total</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <select id="searchChallan" class="form-select js-select2">
                                                            <option value="">All Challans</option>
                                                            @foreach($allChallans as $challan)
                                                                <option value="{{ $challan }}">DR_{{ $challan }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchDrillingName" class="form-select js-select2">
                                                            <option value="">All Drillings</option>
                                                            @foreach($allDrillingNames as $drilling)
                                                                <option value="{{ $drilling->dri_id }}">{{ $drilling->d_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchHoleRate" class="form-select js-select2">
                                                            <option value="">All Hole Rates</option>
                                                            @foreach($allHoleRates as $rate)
                                                                <option value="{{ $rate }}">{{ $rate }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchHoleName" class="form-select js-select2">
                                                            <option value="">All Hole Names</option>
                                                            @foreach($allHoleNames as $name)
                                                                <option value="{{ $name }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchHoleFoot" class="form-select js-select2">
                                                            <option value="">All Hole Foots</option>
                                                            @foreach($allHoleFoots as $foot)
                                                                <option value="{{ $foot }}">{{ $foot }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchHoleTotal" class="form-select js-select2">
                                                            <option value="">All Hole Totals</option>
                                                            @foreach($allHoleTotals as $total)
                                                                <option value="{{ $total }}">{{ $total }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchGrossTotal" class="form-select js-select2">
                                                            <option value="">All Gross Totals</option>
                                                            @foreach($allGrossTotals as $total)
                                                                <option value="{{ $total }}">{{ $total }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableData">
                                                @php $i = $i ?? 0; @endphp
                                                @forelse ($drillings as $key => $drilling)
                                                    <tr>
                                                        <td>DR_{{ $drilling->drilling_id }}</td>
                                                        <td>{{ $drilling->drillingName->d_name ?? 'N/A' }}</td>
                                                        <td>
                                                            @if(!empty($drilling->hole))
                                                            @foreach($drilling->hole as $hole)
                                                            {{ $hole['rate'] ?? '' }}<br>
                                                            @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($drilling->hole))
                                                            @foreach($drilling->hole as $hole)
                                                            {{ $hole['name'] ?? '' }}<br>
                                                            @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($drilling->hole))
                                                            @foreach($drilling->hole as $hole)
                                                            {{ $hole['foot'] ?? '' }}<br>
                                                            @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($drilling->hole))
                                                            @foreach($drilling->hole as $hole)
                                                            {{ $hole['total'] ?? '' }}<br>
                                                            @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $drilling->gross_total }}</td>
                                                        <td>{{ $drilling->date_time ? \Carbon\Carbon::parse($drilling->date_time)->format('d-m-Y h:i A') : '' }}</td>
                                                        <td class="d-flex">
                                                            @can('edit-drilling')
                                                                <a href="{{ route('drilling.edit', $drilling->drilling_id) }}" class="btn btn-sm btn-primary text-white">
                                                                    Edit
                                                                </a>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center">No Record Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-start" id="paginationLinks">
                                    {!! $drillings->links() !!}
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
                    url: "{{ route('drilling.editIndex') }}",
                    type: "GET",
                    data: {
                        page: page,
                        challan: $('#searchChallan').val(),
                        drilling_name: $('#searchDrillingName').val(),
                        date_from: $('#searchDateFrom').val(),
                        date_to: $('#searchDateTo').val(),
                        hole_name: $('#searchHoleName').val(),
                        hole_foot: $('#searchHoleFoot').val(),
                        hole_rate: $('#searchHoleRate').val(),
                        hole_total: $('#searchHoleTotal').val(),
                        gross_total: $('#searchGrossTotal').val()
                    },
                    success: function (response) {
                        let newBody = $(response).find('#tableData').html();
                        $('#tableData').html(newBody);

                        let newPagination = $(response).find('#paginationLinks').html();
                        $('#paginationLinks').html(newPagination);
                    }
                });
            }

            $('#searchChallan, #searchDrillingName, #searchDateFrom, #searchDateTo, #searchHoleName, #searchHoleFoot, #searchHoleRate, #searchHoleTotal, #searchGrossTotal').on('change', function () {
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