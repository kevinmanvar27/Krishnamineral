@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Activity Log Details</h4>   
                            <a class="btn btn-sm btn-primary" href="{{ route('activity-log.index') }}">
                                <i class="bx bx-arrow-to-left"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>User:</th>
                                            <td>{{ $activity->causer ? $activity->causer->name : 'System' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Event:</th>
                                            <td>{{ $activity->event }}</td>
                                        </tr>
                                        <tr>
                                            <th>Subject Type:</th>
                                            <td>{{ $activity->subject_type }}</td>
                                        </tr>
                                        <tr>
                                            <th>Subject ID:</th>
                                            <td>{{ $activity->subject_id }}</td>
                                        </tr>
                                        @if($activity->event == 'created')
                                            <tr>
                                                @if($activity->subject_type === 'App\Models\Sales')
                                                    <th>Created At:</th>
                                                    <td>{{ $sales->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                @endif
                                                @if($activity->subject_type === 'App\Models\Purchase')
                                                    <th>Created At:</th>
                                                    <td>{{ $purchase->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                @endif
                                            </tr>
                                        @endif

                                        @if($activity->event == 'updated')
                                            <tr>
                                                @if($activity->subject_type === 'App\Models\Sales')
                                                    <th>Created At:</th>
                                                    <td>{{ $sales->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                @endif
                                                @if($activity->subject_type === 'App\Models\Purchase')
                                                    <th>Created At:</th>
                                                    <td>{{ $purchase->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                @if($activity->subject_type === 'App\Models\Sales')
                                                    <th>Updated At:</th>
                                                    <td>{{ $sales->updated_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                @endif
                                                @if($activity->subject_type === 'App\Models\Purchase')
                                                    <th>Updated At:</th>
                                                    <td>{{ $purchase->updated_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                                @endif
                                            </tr>
                                        @endif
                                    </table>
                                    <button class="btn btn-primary" id="viewAllLogsBtn">View All Logs</button>
                                </div>
                                <div class="col-md-6">
                                    <h5>Changes</h5>
                                    @if(isset($activity->properties['attributes']) || isset($activity->properties['old']))
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="bg-primary text-white">Attribute</th>
                                                    <th class="bg-primary text-white">Old Value</th>
                                                    <th class="bg-primary text-white">New Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($activity->properties['attributes']))
                                                    @foreach($activity->properties['attributes'] as $key => $newValue)
                                                    <tr>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                        <td>
                                                            @if(isset($activity->properties['old'][$key]))
                                                                @if(is_array($activity->properties['old'][$key]))
                                                                    <pre>{{ json_encode($activity->properties['old'][$key], JSON_PRETTY_PRINT) }}</pre>
                                                                @else
                                                                    {{ $activity->properties['old'][$key] }}
                                                                @endif
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(is_array($newValue))
                                                                <pre>{{ json_encode($newValue, JSON_PRETTY_PRINT) }}</pre>
                                                            @else
                                                                {{ $newValue }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @elseif(isset($activity->properties['old']))
                                                    @foreach($activity->properties['old'] as $key => $oldValue)
                                                    <tr>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                        <td>
                                                            @if(is_array($oldValue))
                                                                <pre>{{ json_encode($oldValue, JSON_PRETTY_PRINT) }}</pre>
                                                            @else
                                                                {{ $oldValue }}
                                                            @endif
                                                        </td>
                                                        <td>-</td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    @else
                                        <p>No changes recorded.</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($activity->description)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Description</h5>
                                    <p>{{ $activity->description }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Related Activity Logs Section -->
                    <div id="relatedLogsSection" style="display: none;">
                        <h4 class="mt-4 mb-3">Related Activity Logs</h4>
                        <div id="relatedLogsContainer">
                            <!-- Related logs will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Include Moment.js and Moment Timezone -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data.min.js"></script>
<script>
    $(document).ready(function() {
        $('#viewAllLogsBtn').on('click', function() {
            const subjectType = "{{ $activity->subject_type }}";
            const subjectId = "{{ $activity->subject_id }}";
            
            // Show loading state
            $('#relatedLogsContainer').html('<div class="card"><div class="card-body text-center">Loading...</div></div>');
            $('#relatedLogsSection').show();
            
            // Scroll to the related logs section
            $('html, body').animate({
                scrollTop: $("#relatedLogsSection").offset().top
            }, 500);
            
            // Properly encode the subject type for URL (including backslashes)
            const encodedSubjectType = encodeURIComponent(subjectType);
            const url = "/activity-log/related/" + encodedSubjectType + "/" + subjectId;
            
            // Fetch related logs
            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#relatedLogsContainer').empty();
                    
                    if (data.length > 0) {
                        // Populate the container with log cards
                        $.each(data, function(index, log) {
                            // Initialize date rows variable
                            let dateRows = '';
                            
                            // Format date with timezone conversion to Asia/Kolkata and format 'd-m-Y h:i A'
                            function formatDateWithTimezone(date) {
                                // Convert to Asia/Kolkata timezone and format
                                return moment(date).tz('Asia/Kolkata').format('DD-MM-YYYY h:mm A');
                            }
                            
                            // Add Created At row based on event type and subject type
                            if (log.event == 'created') {
                                // For created events, show only created at timestamp
                                if ((log.subject_type === 'App\\Models\\Sales' || log.subject_type === 'App\Models\Sales') && log.model_created_at) {
                                    // For Sales, use the model's created_at
                                    dateRows += `<tr>
                                        <th>Created At:</th>
                                        <td>${formatDateWithTimezone(log.model_created_at)}</td>
                                    </tr>`;
                                } else if ((log.subject_type === 'App\\Models\\Purchase' || log.subject_type === 'App\Models\Purchase') && log.model_created_at) {
                                    // For Purchase, use the model's created_at
                                    dateRows += `<tr>
                                        <th>Created At:</th>
                                        <td>${formatDateWithTimezone(log.model_created_at)}</td>
                                    </tr>`;
                                } else {
                                    // For other types or when model data is not available, use the log's created_at
                                    dateRows += `<tr>
                                        <th>Created At:</th>
                                        <td>${formatDateWithTimezone(log.created_at)}</td>
                                    </tr>`;
                                }
                            } else if (log.event == 'updated') {
                                // For updated events, show both created and updated timestamps
                                if ((log.subject_type === 'App\\Models\\Sales' || log.subject_type === 'App\Models\Sales') && log.model_created_at) {
                                    // For Sales, use the model's created_at
                                    dateRows += `<tr>
                                        <th>Created At:</th>
                                        <td>${formatDateWithTimezone(log.model_created_at)}</td>
                                    </tr>`;
                                } else if ((log.subject_type === 'App\\Models\\Purchase' || log.subject_type === 'App\Models\Purchase') && log.model_created_at) {
                                    // For Purchase, use the model's created_at
                                    dateRows += `<tr>
                                        <th>Created At:</th>
                                        <td>${formatDateWithTimezone(log.model_created_at)}</td>
                                    </tr>`;
                                } else {
                                    // For other types or when model data is not available, use the log's created_at
                                    dateRows += `<tr>
                                        <th>Created At:</th>
                                        <td>${formatDateWithTimezone(log.created_at)}</td>
                                    </tr>`;
                                }
                                
                                // Add Updated At row for updated events
                                if ((log.subject_type === 'App\\Models\\Sales' || log.subject_type === 'App\Models\Sales') && log.model_updated_at) {
                                    // For Sales, use the model's updated_at
                                    dateRows += `<tr>
                                        <th>Updated At:</th>
                                        <td>${formatDateWithTimezone(log.model_updated_at)}</td>
                                    </tr>`;
                                } else if ((log.subject_type === 'App\\Models\\Purchase' || log.subject_type === 'App\Models\Purchase') && log.model_updated_at) {
                                    // For Purchase, use the model's updated_at
                                    dateRows += `<tr>
                                        <th>Updated At:</th>
                                        <td>${formatDateWithTimezone(log.model_updated_at)}</td>
                                    </tr>`;
                                } else {
                                    // For other types or when model data is not available, use the log's created_at as updated time
                                    dateRows += `<tr>
                                        <th>Updated At:</th>
                                        <td>${formatDateWithTimezone(log.created_at)}</td>
                                    </tr>`;
                                }
                            }
                            
                            // Create changes table
                            let changesTable = '<p>No changes recorded.</p>';
                            if (log.properties && (log.properties.attributes || log.properties.old)) {
                                let tableRows = '';
                                
                                if (log.properties.attributes) {
                                    $.each(log.properties.attributes, function(key, newValue) {
                                        let oldValue = '-';
                                        if (log.properties.old && log.properties.old[key] !== undefined) {
                                            oldValue = log.properties.old[key];
                                        }
                                        
                                        // Format values for display
                                        let formattedOldValue = oldValue;
                                        let formattedNewValue = newValue;
                                        
                                        if (Array.isArray(oldValue)) {
                                            formattedOldValue = '<pre>' + JSON.stringify(oldValue, null, 2) + '</pre>';
                                        }
                                        
                                        if (Array.isArray(newValue)) {
                                            formattedNewValue = '<pre>' + JSON.stringify(newValue, null, 2) + '</pre>';
                                        }
                                        
                                        tableRows += `
                                            <tr>
                                                <td>${ucfirst(str_replace('_', ' ', key))}</td>
                                                <td>${formattedOldValue}</td>
                                                <td>${formattedNewValue}</td>
                                            </tr>
                                        `;
                                    });
                                } else if (log.properties.old) {
                                    $.each(log.properties.old, function(key, oldValue) {
                                        let formattedOldValue = oldValue;
                                        if (Array.isArray(oldValue)) {
                                            formattedOldValue = '<pre>' + JSON.stringify(oldValue, null, 2) + '</pre>';
                                        }
                                        
                                        tableRows += `
                                            <tr>
                                                <td>${ucfirst(str_replace('_', ' ', key))}</td>
                                                <td>${formattedOldValue}</td>
                                                <td>-</td>
                                            </tr>
                                        `;
                                    });
                                }
                                
                                if (tableRows) {
                                    changesTable = `
                                        <h5>Changes</h5>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="bg-primary text-white">Attribute</th>
                                                    <th class="bg-primary text-white">Old Value</th>
                                                    <th class="bg-primary text-white">New Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                ${tableRows}
                                            </tbody>
                                        </table>
                                    `;
                                }
                            }
                            
                            const logCard = `
                                <div class="card mb-3">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title">Activity Log #${index + 1} (${log.event} -
                                        ${log.event == 'created' ? formatDateWithTimezone(log.model_created_at) : formatDateWithTimezone(log.model_updated_at)})</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <th>User:</th>
                                                        <td>${log.causer ? log.causer.name : 'System'}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Event:</th>
                                                        <td>${log.event}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Subject Type:</th>
                                                        <td>${log.subject_type}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Subject ID:</th>
                                                        <td>${log.subject_id}</td>
                                                    </tr>
                                                    ${dateRows}
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                ${changesTable}
                                            </div>
                                        </div>
                                        
                                        ${log.description ? `
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h5>Description</h5>
                                                <p>${log.description}</p>
                                            </div>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                            `;
                            
                            $('#relatedLogsContainer').append(logCard);
                        });
                    } else {
                        $('#relatedLogsContainer').html('<div class="card"><div class="card-body text-center">No related logs found.</div></div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching related logs:', error);
                    console.error('Response:', xhr.responseText);
                    console.error('URL attempted:', url);
                    $('#relatedLogsContainer').html('<div class="card"><div class="card-body text-center">Error loading logs. Please try again.</div></div>');
                }
            });
        });
        
        // Helper functions for formatting
        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        
        function str_replace(search, replace, subject) {
            return subject.split(search).join(replace);
        }
    });
</script>
@endpush