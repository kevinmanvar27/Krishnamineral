@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="page-wrapper">   
            <div class="page-content">
                <div class="row">
                    <div class="row mb-3">
                        <!-- [Leads] start -->
                        <div class="col-xxl-12">
                            @session('success')
                                <div class="alert alert-success" role="alert"> 
                                    {{ $value }}
                                </div>
                            @endsession
                            <div class="card stretch stretch-full">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title mb-0">Edit Vendors</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="border-b">
                                                    <th>No</th>
                                                    <th>Vendor Code</th>
                                                    <th>Vendor Name</th>
                                                    <th>Contact Person</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Action</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th>
                                                        <select id="searchVendorCodeFilter" class="form-control js-select2">
                                                            <option value="">All Vendor Codes</option>
                                                            @foreach($vendorCodes as $code)
                                                                <option value="{{ $code }}">VEN_{{ $code }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchVendorNameFilter" class="form-control js-select2">
                                                            <option value="">All Vendor Names</option>
                                                            @foreach($vendorNames as $name)
                                                                <option value="{{ $name }}">{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchContactPersonFilter" class="form-control js-select2">
                                                            <option value="">All Contact Persons</option>
                                                            @foreach($contactPersons as $person)
                                                                <option value="{{ $person }}">{{ $person }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchMobileFilter" class="form-control js-select2">
                                                            <option value="">All Mobile Numbers</option>
                                                            @foreach($mobileNumbers as $mobile)
                                                                <option value="{{ $mobile }}">{{ $mobile }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <select id="searchEmailFilter" class="form-control js-select2">
                                                            <option value="">All Emails</option>
                                                            @foreach($emails as $email)
                                                                <option value="{{ $email }}">{{ $email }}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableData">
                                                @php $i = ($vendors->currentPage() - 1) * $vendors->perPage(); @endphp
                                                @forelse ($vendors as $key => $vendor)
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td>VEN_{{ $vendor->vendor_code }}</td>
                                                        <td>{{ $vendor->vendor_name }}</td>
                                                        <td>{{ $vendor->contact_person }}</td>
                                                        <td>{{ $vendor->mobile }}</td>
                                                        <td>{{ $vendor->email_id }}</td>
                                                        <td class="d-flex">
                                                            @can('edit-vendor')
                                                                <a href="{{ route('vendors.edit', $vendor->id) }}" class="btn btn-sm btn-primary text-white">
                                                                    Edit
                                                                </a>
                                                            @endcan
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
                                    {!! $vendors->links() !!}
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
                    url: "{{ route('vendors.editIndex') }}",
                    type: "GET",
                    data: {
                        page: page,
                        vendor_code: $('#searchVendorCodeFilter').val(),
                        vendor_name: $('#searchVendorNameFilter').val(),
                        contact_person: $('#searchContactPersonFilter').val(),
                        mobile: $('#searchMobileFilter').val(),
                        email: $('#searchEmailFilter').val()
                    },
                    success: function (response) {
                        let newBody = $(response).find('#tableData').html();
                        $('#tableData').html(newBody);

                        let newPagination = $(response).find('#paginationLinks').html();
                        $('#paginationLinks').html(newPagination);
                    }
                });
            }

            $('#searchVendorCodeFilter, #searchVendorNameFilter, #searchContactPersonFilter, #searchMobileFilter, #searchEmailFilter').on('change', function () {
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