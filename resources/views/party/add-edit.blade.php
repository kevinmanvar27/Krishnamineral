@extends('layouts.app')

@section('content')

    <main class="wrapper">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="page-content">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            </div>
                        @endif
                        @session('success')
                            <div class="alert alert-success" role="alert"> 
                                {{ $value }}
                            </div>
                        @endsession
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Party</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ isset($party) ? route('party.editIndex') : route('party.index') }}">
                                            <i class="bx bx-arrow-to-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ isset($party) ? route('party.update', $party->id) : route('party.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if (isset($party))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">    
                                    <div class="row align-items-center d-flex justify-content-between">
                                        <div class="col-lg-6 mb-4">
                                            <label for="name" class="form-label mb-2">Party</label>
                                            <div class="input-group">
                                                <input type="text" name="name" placeholder="Enter Party Name" class="form-control"  id="name"  value="{{ old('name', $party->name ?? '') }}">
                                            </div>
                                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="name" class="form-label mb-2">Sales By</label>
                                            <div class="input-group">
                                                <select name="sales_by" id="" class="form-control">
                                                    <option value="">Select Sales By</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}" {{ old('sales_by', $party->sales_by ?? '') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <h5>Party Persions</h5>
                                    <div class="row align-items-center">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="myTable">
                                                    <thead>
                                                        <tr class="align-items-center">
                                                            <th style="min-width: 250px;">Persion</th>
                                                            <th style="min-width: 300px;">Persion Contact Number</th>
                                                            <th><button id="addTableRow" type="button" class="btn btn-sm btn-success"><i class="bx bx-plus"></i> Add Row</button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($party) && $party->items)
                                                            @foreach($party->items as $index => $item)
                                                                <tr>
                                                                    <td><input type="text" class="form-control" name="persions[]" placeholder="Enter Persion Name" value="{{ $item->persions }}"></td>
                                                                    <td><input type="text" class="form-control" name="persion_contact_number[]" placeholder="Enter Persion Contact Number" value="{{ $item->persion_contact_number }}"></td>
                                                                    <td><button class="btn btn-danger btn-md deleteRow"><i class="bx bx-trash"></i></button></td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td><input type="text" class="form-control" name="persions[]" placeholder="Enter Persion Name"></td>
                                                                <td><input type="text" class="form-control" name="persion_contact_number[]" placeholder="Enter Persion Contact Number"></td>
                                                                <td><button class="btn btn-danger btn-md deleteRow"><i class="bx bx-trash"></i></button></td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-between">
                                        <div class="col-lg-3">
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="bx bx-save me-2"></i>
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
    $(document).ready(function() { 

        $('#addTableRow').click(function() {
            let newRow = `
                <tr>
                    <td><input type="text" class="form-control" name="persions[]" placeholder="Enter Persion Name"></td>
                    <td><input type="text" class="form-control" name="persion_contact_number[]" placeholder="Enter Persion Contact Number"></td>
                    <td><button class="btn btn-danger btn-md deleteRow"><i class="bx bx-trash"></i></button></td>
                    
                </tr>
            `;
            $('#myTable tbody').append(newRow);
            initSelect2($('#myTable tbody tr:last'));
        });

        $('#myTable').on('click', '.deleteRow', function() {
            $(this).closest('tr').remove();
        });

        function updateRowNumbers() {
            rowCount = 1;
            $('#myTable tbody tr').each(function() {
                $(this).find('td:first').text(rowCount);
                rowCount++;
            });
        }
    })
</script>
@endpush