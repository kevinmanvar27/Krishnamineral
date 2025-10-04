@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">   
            <div class="page-content">
                <div class="row">
                    <div class="row mb-3">
                        @php
                            $i = ($drivers->currentPage() - 1) * $drivers->perPage();
                        @endphp
                        <!-- [Leads] start -->
                        <div class="col-xxl-12">
                            @session('success')
                                <div class="alert alert-success" role="alert"> 
                                    {{ $value }}
                                </div>
                            @endsession
                            <div class="card stretch stretch-full">
                                <div class="card-header">
                                    <h5 class="card-title">Drivers</h5>
                                </div>  
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="border-b">
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Driver Type</th>
                                                    <th>Contact Number</th>
                                                    <th>Created AT</th>
                                                    <th>Action</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th>
                                                        <select id="searchName" class="form-select js-select2">
                                                            <option value="">All Names</option>
                                                            @foreach($allNames as $name)
                                                                <option value="{{ $name }}" {{ old('name') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchDriver" class="form-select js-select2">
                                                            <option value="">All Driver Types</option>
                                                            @foreach($allDrivers as $driver)
                                                                <option value="{{ $driver }}" {{ old('driver') == $driver ? 'selected' : '' }}>{{ $driver }}</option>
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
                                                    <th>
                                                        <select id="searchDate" class="form-select js-select2">
                                                            <option value="">All Dates</option>
                                                            @foreach($allDates->unique() as $date)
                                                                <option value="{{ $date }}" {{ old('date') == $date ? 'selected' : '' }}>{{ $date }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableData">
                                                @php $i = $i ?? 0; @endphp
                                                @forelse ($drivers as $key => $driver)
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $driver->name }}</td>
                                                        <td>{{ $driver->driver }}</td>
                                                        <td>{{ $driver->contact_number }}</td>
                                                        <td>{{ $driver->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                        <td class="d-flex">
                                                            <a class="btn btn-info btn-sm me-2" href="{{ route('driver.show',$driver->id) }}">
                                                                <i class="lni lni-eye text-white"></i>
                                                            </a>
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
                                    {!! $drivers->links() !!}
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
                    url: "{{ route('driver.index') }}",
                    type: "GET",
                    data: {
                        page: page,
                        name: $('#searchName').val(),
                        date: $('#searchDate').val(),
                        driver: $('#searchDriver').val(),
                        contact_number: $('#searchContact').val(),
                    },
                    success: function (response) {
                        let newBody = $(response).find('#tableData').html();
                        $('#tableData').html(newBody);

                        let newPagination = $(response).find('#paginationLinks').html();
                        $('#paginationLinks').html(newPagination);
                    }
                });
            }

            $('#searchName, #searchDate, #searchDriver, #searchContact' ).on('change', function () {
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