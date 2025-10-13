@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">   
            <div class="page-content">
                <div class="row">
                    <div class="row mb-3">
                        @php
                        $i = ($materials->currentPage() - 1) * $materials->perPage();
                        @endphp
                        <!-- [Leads] start -->
                        <div class="col-xxl-12">
                            @session('success')
                                <div class="alert alert-success" role="alert"> 
                                    {{ session('success') }}
                                </div>
                            @endsession
                            <div class="card stretch stretch-full">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Materials Edit</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr class="border-b">
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Updated AT</th>
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
                                                @forelse($materials as $material)
                                                    @if($material)
                                                        <tr>
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ $material->name ?? '' }}</td>
                                                            <td>{{ $material->updated_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                            <td>
                                                                <a href="{{ route('purchaseMaterials.edit', $material->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                            </td>
                                                        </tr>
                                                    @endif
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
                                    {!! $materials->links() !!}
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
                    url: "{{ route('purchaseMaterials.editIndex') }}",
                    type: "GET",
                    data: {
                        page: page,
                        name: $('#searchName').val(),
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

            $('#searchName, #searchDate').on('change', function () {
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