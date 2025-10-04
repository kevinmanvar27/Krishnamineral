@extends('layouts.app')

@section('content')

<div class="wrapper">
    <div class="page-wrapper">   
        <div class="page-content">
            <div class="row">
                <div class="row mb-3">
                    @php
                    $i = ($vehicles->currentPage() - 1) * $vehicles->perPage();
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
                                <h5 class="card-title">Vehicles Edit</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="border-b">
                                                <th>Name</th>
                                                <th>Transporter Name</th>
                                                <th>Contact Number</th>
                                                <th>Updated AT</th>
                                                <th>Actions</th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <select id="searchName" class="form-select js-select2">
                                                        <option value="">All Names</option>
                                                        @foreach($allNames as $name)
                                                            <option value="{{ $name }}" {{ old('name') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchVehicle" class="form-select js-select2">
                                                        <option value="">All Transporters</option>
                                                        @foreach($allVehicles as $driver)
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
                                            @forelse ($vehicles as $vehicle)
                                                <tr>
                                                    <td>{{ $vehicle->name }}</td>
                                                    <td>{{ $vehicle->vehicle_name }}</td>
                                                    <td>{{ $vehicle->contact_number }}</td>
                                                    <td>{{ $vehicle->updated_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                    <td class="d-flex">
                                                        <a href="{{ route('vehicle.edit', $vehicle->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No Record Found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-start" id="paginationLinks">
                                {!! $vehicles->links() !!}
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
                    url: "{{ route('vehicles.editIndex') }}",
                    type: "GET",
                    data: {
                        page: page,
                        name: $('#searchName').val(),
                        date: $('#searchDate').val(),
                        vehicle_name: $('#searchVehicle').val(),
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

            $('#searchName, #searchDate, #searchVehicle, #searchContact' ).on('change', function () {
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