@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">   
            <div class="page-content">
                <div class="row">
                    <div class="row mb-3">
                        @php
                        $i = ($data->currentPage() - 1) * $data->perPage();
                        @endphp
                        <!-- [Leads] start -->
                        <div class="col-xxl-12">
                            @if(session('auto_download_pdf') && session('pdf_user_id'))
                                <script>
                                    window.addEventListener('DOMContentLoaded', function () {
                                        const pdfUrl = "{{ route('users.credentials-pdf', session('pdf_user_id')) }}";
                                        const a = document.createElement('a');
                                        a.href = pdfUrl;
                                        a.download = 'User_Credentials.pdf';
                                        document.body.appendChild(a);
                                        a.click();
                                        document.body.removeChild(a);
                                    });
                                </script>
                            @endif
                            @session('success')
                                <div class="alert alert-success" role="alert"> 
                                    {{ session('success') }}
                                </div>
                            @endsession
                            <div class="card stretch stretch-full">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Employees</h5>
                                    <div class="card-header-action">                      
                                        @can('employee-create')
                                            <a class="btn btn-success btn-sm" href="{{ route('users.create') }}">
                                                <i class="fa fa-plus"></i> Create New Employee
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="border-b">
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Emails</th>
                                                    <th>Last Login</th>
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
                                                        <select id="searchEmail" class="form-select js-select2">
                                                            <option value="">All Emails</option>
                                                            @foreach($allEmails as $email)
                                                                <option value="{{ $email }}" {{ old('email') == $email ? 'selected' : '' }}>{{ $email }}</option>
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
                                                @forelse ($data as $key => $user)
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->last_login_at ? $user->last_login_at->timezone('Asia/Kolkata')->format('d M Y, H:i:s') : '' }}</td>
                                                        <td class="d-flex">
                                                            <a class="btn btn-info btn-sm me-2" href="{{ route('users.show',$user->id) }}">
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
                                    {!! $data->links() !!}
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
                    url: "{{ route('users.index') }}",
                    type: "GET",
                    data: {
                        page: page,
                        name: $('#searchName').val(),
                        email: $('#searchEmail').val(),
                        date: $('#searchDate').val()
                    },
                    success: function (response) {
                        let newBody = $(response).find('#tableData').html();
                        $('#tableData').html(newBody);

                        let newPagination = $(response).find('#paginationLinks').html();
                        $('#paginationLinks').html(newPagination);
                    }
                });
            }

            $('#searchName, #searchEmail, #searchDate').on('change', function () {
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