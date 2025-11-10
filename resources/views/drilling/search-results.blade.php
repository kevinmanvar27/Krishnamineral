<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr class="border-b">
                <th>Challan Number</th>
                <th>Drilling Name</th>
                <th>Date Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($results->count() > 0)
                @foreach($results as $result)
                <tr>
                    <td>{{ "D_".$result->drilling_id }}</td>
                    <td>{{ $result->drillingName ? $result->drillingName->d_name : '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($result->date_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                    <td class="d-flex">
                        @can('view-drilling')
                            <a class="btn btn-info btn-sm me-2" href="{{ route('drilling.show',$result->drilling_id) }}">
                                <i class="lni lni-eye text-white"></i>
                            </a>
                        @endcan
                        @can('edit-drilling')
                            <a class="btn btn-warning btn-sm me-2" href="{{ route('drilling.edit',$result->drilling_id) }}">
                                <i class="lni lni-pencil text-white"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No results found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>