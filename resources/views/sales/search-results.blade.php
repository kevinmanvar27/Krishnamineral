<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr class="border-b">
                <th>Challan Number</th>
                <th>Transporter</th>
                <th>Contact Number</th>
                <th>Created AT</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($results->count() > 0)
                @foreach($results as $result)
                <tr>
                    <td>{{ $result->id }}</td>
                    <td>{{ $result->transporter }}</td>
                    <td>{{ $result->contact_number }}</td>
                    <td>{{ $result->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                    <td>
                        @if($result->status == 0)
                            <span class="badge bg-warning">Pending</span>
                        @elseif($result->status == 1)
                            <span class="badge bg-success">Completed</span>
                        @elseif($result->status == 2)
                            <span class="badge bg-info">Audit</span>
                        @else
                            <span class="badge bg-secondary">Unknown</span>
                        @endif
                    </td>
                    <td class="d-flex">
                        @can('view-sales')
                            <a class="btn btn-info btn-sm me-2" href="{{ route('sales.show',$result->id) }}">
                                <i class="lni lni-eye text-white"></i>
                            </a>
                        @endcan
                        @can('edit-sales')
                            @if($result->status == 0)
                                <a class="btn btn-warning btn-sm me-2" href="{{ route('sales.edit',$result->id) }}">
                                    <i class="lni lni-pencil text-white"></i>
                                </a>
                            @elseif($result->status == 1)
                                <a class="btn btn-warning btn-sm me-2" href="{{ route('sales.edit',$result->id) }}">
                                    <i class="lni lni-pencil text-white"></i>
                                </a>
                            @endif
                        @endcan
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">No results found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>