@extends('layouts.app')

@section('content')
    @if(session('auto_download_pdf') && session('pdf_blasting_id'))
        <script>
            window.addEventListener('DOMContentLoaded', function () {
                const pdfUrl = "{{ route('blasting.blasting-pdf', session('pdf_blasting_id')) }}";
                const a = document.createElement('a');
                a.href = pdfUrl;
                a.download = 'Blasting.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        </script>
        @php
            session()->forget(['auto_download_pdf', 'pdf_blasting_id']);
        @endphp
    @endif

    <div class="wrapper">
        <div class="page-wrapper">   
            <div class="page-content">
                <div class="row">
                    <div class="row mb-3">
                        @php
                            $i = ($blastings->currentPage() - 1) * $blastings->perPage();
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
                                    <h5 class="card-title mb-0">Blasting - Edit Records</h5>
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
                                                    <th>Blaster Name</th>
                                                    <th>Geliten</th>
                                                    <th>Geliten Rate</th>
                                                    <th>Geliten Total</th>
                                                    <th>DF</th>
                                                    <th>DF Rate</th>
                                                    <th>DF Total</th>
                                                    <th>OD Vat</th>
                                                    <th>OD Rate</th>
                                                    <th>OD Total</th>
                                                    <th>Controll</th>
                                                    <th>Controll Meter</th>
                                                    <th>Controll Rate</th>
                                                    <th>Controll Total</th>
                                                    <th>Gross Total</th>
                                                    <th>Date Time</th>
                                                    <th>Action</th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <select id="searchChallan" class="form-select js-select2">
                                                            <option value="">All Challans</option>
                                                            @foreach($allChallans as $challan)
                                                                <option value="{{ $challan }}">BL_{{ $challan }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchBlasterName" class="form-select js-select2">
                                                            <option value="">All Blasters</option>
                                                            @foreach($allBlasterNames as $blaster)
                                                                <option value="{{ $blaster->bnm_id }}">{{ $blaster->b_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchGeliten" class="form-select js-select2">
                                                            <option value="">All Geliten</option>
                                                            @foreach($allGeliten as $geliten)
                                                                <option value="{{ $geliten }}">{{ $geliten }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchGelitenRate" class="form-select js-select2">
                                                            <option value="">All Geliten Rates</option>
                                                            @foreach($allGelitenRates as $rate)
                                                                <option value="{{ $rate }}">{{ $rate }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchGelitenTotal" class="form-select js-select2">
                                                            <option value="">All Geliten Totals</option>
                                                            @foreach($allGelitenTotals as $total)
                                                                <option value="{{ $total }}">{{ $total }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchDf" class="form-select js-select2">
                                                            <option value="">All DF</option>
                                                            @foreach($allDfs as $df)
                                                                <option value="{{ $df }}">{{ $df }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchDfRate" class="form-select js-select2">
                                                            <option value="">All DF Rates</option>
                                                            @foreach($allDfRates as $rate)
                                                                <option value="{{ $rate }}">{{ $rate }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchDfTotal" class="form-select js-select2">
                                                            <option value="">All DF Totals</option>
                                                            @foreach($allDfTotals as $total)
                                                                <option value="{{ $total }}">{{ $total }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchOdvat" class="form-select js-select2">
                                                            <option value="">All OD Vat</option>
                                                            @foreach($allOdvats as $odvat)
                                                                <option value="{{ $odvat }}">{{ $odvat }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchOdRate" class="form-select js-select2">
                                                            <option value="">All OD Rates</option>
                                                            @foreach($allOdRates as $rate)
                                                                <option value="{{ $rate }}">{{ $rate }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchOdTotal" class="form-select js-select2">
                                                            <option value="">All OD Totals</option>
                                                            @foreach($allOdTotals as $total)
                                                                <option value="{{ $total }}">{{ $total }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchControllName" class="form-select js-select2">
                                                            <option value="">All Controll Names</option>
                                                            @foreach($allControllNames as $name)
                                                                <option value="{{ $name }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchControllMeter" class="form-select js-select2">
                                                            <option value="">All Controll Meters</option>
                                                            @foreach($allControllMeters as $meter)
                                                                <option value="{{ $meter }}">{{ $meter }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchControllRate" class="form-select js-select2">
                                                            <option value="">All Controll Rates</option>
                                                            @foreach($allControllRates as $rate)
                                                                <option value="{{ $rate }}">{{ $rate }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchControllTotal" class="form-select js-select2">
                                                            <option value="">All Controll Totals</option>
                                                            @foreach($allControllTotals as $total)
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
                                                @forelse ($blastings as $key => $blasting)
                                                    <tr>
                                                        <td>BL_{{ $blasting->blasting_id }}</td>
                                                        <td>{{ $blasting->blasterName->b_name ?? '' }}</td>
                                                        <td>{{ $blasting->geliten }}</td>
                                                        <td>{{ $blasting->geliten_rate }}</td>
                                                        <td>{{ $blasting->geliten_total }}</td>
                                                        <td>{{ $blasting->df }}</td>
                                                        <td>{{ $blasting->df_rate }}</td>
                                                        <td>{{ $blasting->df_total }}</td>
                                                        <td>{{ $blasting->odvat }}</td>
                                                        <td>{{ $blasting->od_rate }}</td>
                                                        <td>{{ $blasting->od_total }}</td>
                                                        <td>
                                                            @if(!empty($blasting->controll))
                                                                @foreach($blasting->controll as $control)
                                                                    {{ $control['name'] ?? '' }}<br>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($blasting->controll))
                                                                @foreach($blasting->controll as $control)
                                                                    {{ $control['meter'] ?? '' }}<br>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($blasting->controll))
                                                                @foreach($blasting->controll as $control)
                                                                    {{ $control['rate'] ?? '' }}<br>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($blasting->controll))
                                                                @foreach($blasting->controll as $control)
                                                                    {{ $control['total'] ?? '' }}<br>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $blasting->gross_total }}</td>
                                                        <td>{{ $blasting->date_time ? \Carbon\Carbon::parse($blasting->date_time)->format('d-m-Y h:i A') : '' }}</td>
                                                        <td class="d-flex">
                                                            <a href="{{ route('blasting.edit', $blasting->blasting_id) }}" class="btn btn-primary btn-sm me-2">Edit</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="18" class="text-center">No Record Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-start" id="paginationLinks">
                                    {!! $blastings->links() !!}
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
                    url: "{{ route('blasting.editIndex') }}",
                    type: "GET",
                    data: {
                        page: page,
                        challan: $('#searchChallan').val(),
                        blaster_name: $('#searchBlasterName').val(),
                        notes: $('#searchNotes').val(),
                        date_from: $('#searchDateFrom').val(),
                        date_to: $('#searchDateTo').val(),
                        geliten: $('#searchGeliten').val(),
                        geliten_rate: $('#searchGelitenRate').val(),
                        geliten_total: $('#searchGelitenTotal').val(),
                        df: $('#searchDf').val(),
                        df_rate: $('#searchDfRate').val(),
                        df_total: $('#searchDfTotal').val(),
                        odvat: $('#searchOdvat').val(),
                        od_rate: $('#searchOdRate').val(),
                        od_total: $('#searchOdTotal').val(),
                        controll_name: $('#searchControllName').val(),
                        controll_meter: $('#searchControllMeter').val(),
                        controll_rate: $('#searchControllRate').val(),
                        controll_total: $('#searchControllTotal').val(),
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

            $('#searchChallan, #searchBlasterName, #searchNotes, #searchDateFrom, #searchDateTo, #searchGeliten, #searchGelitenRate, #searchGelitenTotal, #searchDf, #searchDfRate, #searchDfTotal, #searchOdvat, #searchOdRate, #searchOdTotal, #searchControllName, #searchControllMeter, #searchControllRate, #searchControllTotal, #searchGrossTotal').on('change', function () {
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