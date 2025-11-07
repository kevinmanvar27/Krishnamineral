@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">Activity Logs</h4>
                            <div class="d-flex gap-2">
                                <input type="date" id="searchDateFrom" class="form-control" placeholder="From Date" value="{{ request('from_date') }}">
                                <input type="date" id="searchDateTo" class="form-control" placeholder="To Date" value="{{ request('to_date') }}">
                            </div>
                        </div>
                        <div class="card-body">
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="activityLogTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Event</th>
                                            <th>Subject</th>
                                            <th>Description</th>
                                            <th>Created At</th>
                                            @can('view-activity-log')
                                            <th>Actions</th>
                                            @endcan
                                        </tr>
                                        <!-- Filter Row -->
                                        <tr>
                                            <th></th> <!-- # column -->
                                            <th>
                                                <select class="form-control js-select2 filter-input" data-column="1">
                                                    <option value="">All Users</option>
                                                    @foreach($activities->pluck('causer.name')->unique()->filter() as $user)
                                                        <option value="{{ $user }}">{{ $user ?? 'System' }}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th>
                                                <select class="form-control js-select2 filter-input" data-column="2">
                                                    <option value="">All Events</option>
                                                    @foreach($activities->pluck('event')->unique()->filter() as $event)
                                                        <option value="{{ $event }}">{{ $event }}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th>
                                                <select class="form-control js-select2 filter-input" data-column="3">
                                                    <option value="">All Subjects</option>
                                                    @foreach($activities->pluck('subject_type')->unique()->filter() as $subject)
                                                        <option value="{{ $subject }}">{{ $subject }}</option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th>
                                                <input type="text" class="form-control filter-input" placeholder="Filter Description" data-column="4">
                                            </th>
                                            <th>
                                                <input type="date" class="form-control filter-input" data-column="5">
                                            </th>
                                            @can('view-activity-log')
                                            <th></th> <!-- Actions column -->
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody id="tableData">
                                        @forelse($activities as $activity)
                                        <tr>
                                            <td>{{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}</td>
                                            <td>{{ $activity->causer ? $activity->causer->name : 'System' }}</td>
                                            <td>{{ $activity->event }}</td>
                                            <td>{{ $activity->subject_type }}</td>
                                            <td>{{ Str::limit($activity->description, 50) }}</td>
                                            <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                            @can('view-activity-log')
                                            <td>
                                                <a href="{{ route('activity-log.show', $activity->id) }}" class="btn btn-info btn-sm">
                                                    <i class="lni lni-eye text-white"></i>
                                                </a>
                                            </td>
                                            @endcan
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No activity logs found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                {{ $activities->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Existing date filter functionality
        function fetch_data() {
            let fromDate = $('#searchDateFrom').val();
            let toDate = $('#searchDateTo').val();
            let causer = $('#causer').val();
            
            let params = new URLSearchParams();
            
            if (fromDate) {
                params.append('from_date', fromDate);
            }
            
            if (toDate) {
                params.append('to_date', toDate);
            }
            
            if (causer) {
                params.append('causer', causer);
            }
            
            let newUrl = "{{ route('activity-log.index') }}";
            if (params.toString()) {
                newUrl += '?' + params.toString();
            }
            
            window.location.href = newUrl;
        }

        $('#searchDateFrom, #searchDateTo').on('change', function () {
            fetch_data();
        });
        
        // Column-based filtering
        $('.filter-input').on('change keyup', function() {
            filterTable();
        });
        
        function filterTable() {
            let table = $('#activityLogTable');
            let rows = $('#tableData tr');
            
            rows.each(function() {
                let row = $(this);
                let showRow = true;
                
                $('.filter-input').each(function() {
                    let filterValue = $(this).val().toLowerCase();
                    let columnIndex = $(this).data('column');
                    
                    if (filterValue !== '') {
                        let cellText = row.find('td').eq(columnIndex).text().toLowerCase();
                        
                        // For select inputs, match exact value
                        if ($(this).is('select')) {
                            if (cellText !== filterValue) {
                                showRow = false;
                                return false; // Break the loop
                            }
                        } else {
                            // For text inputs, check if cell contains the filter value
                            if (cellText.indexOf(filterValue) === -1) {
                                showRow = false;
                                return false; // Break the loop
                            }
                        }
                    }
                });
                
                if (showRow) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }
    });
</script>
@endpush