<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr class="border-b">
                <th>Challan Number</th>
                <th>Blaster Name</th>
                <th>Date Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($results->count() > 0)
                @foreach($results as $result)
                <tr>
                    <td>{{ "B_".$result->blasting_id }}</td>
                    <td>{{ $result->blasterName ? $result->blasterName->b_name : '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($result->date_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                    <td class="d-flex">
                        @can('view-blasting')
                            <a class="btn btn-info btn-sm me-2" href="{{ route('blasting.show',$result->blasting_id) }}">
                                <i class="lni lni-eye text-white"></i>
                            </a>
                        @endcan
                        @can('edit-blasting')
                            <a class="btn btn-warning btn-sm me-2" href="{{ route('blasting.edit',$result->blasting_id) }}">
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