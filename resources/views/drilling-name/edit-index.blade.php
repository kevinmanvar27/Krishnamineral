@extends('layouts.app')

@section('content')

    <main class="wrapper">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        @session('success')
                            <div class="alert alert-success" role="alert"> 
                                {{ $value }}
                            </div>
                        @endsession
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Drilling Names</h5>
                                <div class="card-header-action">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Drilling Name</th>
                                                <th>Phone Number</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>
                                                    <select id="searchName" class="form-select js-select2">
                                                        <option value="">All Names</option>
                                                        @foreach($allNames as $name)
                                                            <option value="{{ $name }}" {{ request('d_name') == $name ? 'selected' : '' }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchPhone" class="form-select js-select2">
                                                        <option value="">All Phones</option>
                                                        @foreach($allPhones as $phone)
                                                            <option value="{{ $phone }}" {{ request('phone_no') == $phone ? 'selected' : '' }}>{{ $phone }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th>
                                                    <select id="searchStatus" class="form-select js-select2">
                                                        <option value="">All Statuses</option>
                                                        @foreach($allStatuses as $status)
                                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableData">
                                            @php $i = $i ?? 0; @endphp
                                            @forelse($drillingNames as $drillingName)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $drillingName->d_name }}</td>
                                                <td>{{ $drillingName->phone_no }}</td>
                                                <td>
                                                    @if($drillingName->status == 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('drilling-name.edit', $drillingName->dri_id) }}" class="btn btn-primary btn-sm">
                                                        Edit
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No drilling names found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-start" id="paginationLinks">
                                    {!! $drillingNames->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            function fetch_data(page = 1) {
                $.ajax({
                    url: "{{ route('drilling-name.editIndex') }}",
                    type: "GET",
                    data: {
                        page: page,
                        d_name: $('#searchName').val(),
                        phone_no: $('#searchPhone').val(),
                        status: $('#searchStatus').val(),
                    },
                    success: function (response) {
                        let newBody = $(response).find('#tableData').html();
                        $('#tableData').html(newBody);
                        
                        let newPagination = $(response).find('#paginationLinks').html();
                        $('#paginationLinks').html(newPagination);
                    }
                });
            }

            $('#searchName, #searchPhone, #searchStatus').on('change', function () {
                fetch_data();
            });
            
            $(document).on('click', '#paginationLinks .pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });
        });
    </script>