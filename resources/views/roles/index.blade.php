@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <div class="page-wrapper">   
            <div class="page-content">
                <div class="row">
                    @php
                        $i = ($roles->currentPage() - 1) * $roles->perPage();
                    @endphp
                    <!-- [Leads] start -->
                    <div class="col-xxl-12">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card stretch stretch-full">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Roles</h5>
                                @can('role-create')
                                    <a class="btn btn-success btn-sm" href="{{ route('roles.create') }}">
                                        <i class="fa fa-plus"></i> Create New Role
                                    </a>
                                @endcan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr class="border-b">
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $key => $role)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $role->name }}</td>
                                                    <td class="d-flex">
                                                        <a class="btn btn-info btn-sm me-2" href="{{ route('roles.show', $role->id) }}">
                                                            <i class="lni lni-eye text-white"></i>
                                                        </a>
                                                        @can('role-edit')
                                                            <a class="btn btn-primary btn-sm me-2" href="{{ route('roles.edit', $role->id) }}">
                                                                <i class="bx bx-edit-alt"></i>
                                                            </a>
                                                        @endcan
                                                        @can('role-delete')
                                                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-start">
                                    {!! $roles->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [Leads] end -->
                </div>
            </div>
        </div>
    </div>
@endsection