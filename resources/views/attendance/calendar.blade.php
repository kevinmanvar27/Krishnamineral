@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Attendance Calendar</h5>
                            <div class="card-header-action">
                                <!-- Start Time End Time Countdown Timer -->
                                <span class="d-none" id="startTime">{{ auth()->user()->attendance_start_time ?? '00:00:00' }}</span>
                                <span class="d-none" id="endTime">{{ auth()->user()->attendance_end_time ?? '23:59:59' }}</span>
                                <div id="startTimeEndTimeCountdown" class="btn btn-warning me-2"></div>
                                <!-- Print Button -->
                                <button type="button" class="btn btn-secondary me-2" id="printCalendar">
                                    <i class="lni lni-printer"></i> Print
                                </button>
                                @if(auth()->user()->can('add-attendance'))
                                    @php
                                        $currentTime = now()->timezone('Asia/Kolkata')->format('H:i:s');
                                        $startTime = auth()->user()->attendance_start_time ?? '00:00:00';
                                        $endTime = auth()->user()->attendance_end_time ?? '23:59:59';
                                    @endphp
                                    @if( ($currentTime >= $startTime) && ( $currentTime <= $endTime) )
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal" id="openAddAttendanceModal">
                                            <i class="lni lni-plus"></i> Add Attendance
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-primary disabled" data-bs-toggle="modal">
                                            <i class="lni lni-plus"></i> Add Attendance
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filter Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <form id="attendanceCalendarForm" method="GET" action="{{ route('attendance.calendar') }}">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label for="month_year" class="form-label">Month & Year</label>
                                                <input type="month" class="form-control" id="month_year" name="month_year" 
                                                       value="{{ sprintf('%04d-%02d', $year, $month) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="user_id" class="form-label">Filter by Employee</label>
                                                <select class="form-select js-select2" id="user_id" name="user_id">
                                                    @foreach($allEmployees as $employee)
                                                        <option value="{{ $employee->id }}" {{ isset($userId) && $userId == $employee->id ? 'selected' : '' }}>
                                                            {{ ucwords($employee->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Current User Information -->
                            @if(isset($currentUser))
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Employee ID :</strong> KME_{{ $currentUser->id ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p class="mb-1"><strong>Employee Name :</strong> {{ ucfirst($currentUser->name) ?? 'N/A' }}</p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        @php
                                                            $designation = isset($roles[$currentUser->user_type]) ? strtoupper($roles[$currentUser->user_type]) : 'TYPE ' . ($currentUser->user_type ?? 'N/A');
                                                        @endphp
                                                        <p class="mb-1"><strong>Employee Designation :</strong> {{ $designation }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(isset($employeeAttendances))
                            <div class="table-responsive">
                                <table class="table table-bordered attendance-calendar-table">
                                    <thead>
                                        <tr>
                                            <th colspan="1" class="bg-primary text-white">{{ date('M-Y', mktime(0, 0, 0, $month, 1, $year)) }}</th>
                                            @php
                                                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                            @endphp
                                            @for($day = 1; $day <= $daysInMonth; $day++)
                                                <th>{{ sprintf('%02d', $day) }}</th>
                                            @endfor
                                            <th class="bg-primary text-white">Total</th>
                                            <th class="bg-primary text-white">Salary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employeeAttendances as $empAttendance)
                                        @php
                                            $employee = $empAttendance['employee'];
                                            $records = $empAttendance['records'];
                                            
                                            // Calculate totals
                                            $presentCount = 0;
                                            $overtimeTotal = 0;
                                            $tripTotal = 0;
                                            
                                            foreach($records as $record) {
                                                if($record) {
                                                    if($record->type_attendance == 1) {
                                                        $presentCount++;
                                                    }
                                                    $overtimeTotal += $record->extra_hours;
                                                    $tripTotal += $record->driver_tuck_trip;
                                                }
                                            }
                                            
                                            // Calculate salary (assuming base salary logic)
                                            $baseSalaryPerDay = 1000; // Adjust as needed
                                            $overtimeRate = 62.5; // 625 for 10 hours = 62.5 per hour
                                            $salary = ($presentCount * $baseSalaryPerDay) + ($overtimeTotal * $overtimeRate);
                                        @endphp
                                        <!-- Main Employee Row -->
                                        <tr>
                                            <td>Attendance</td>
                                            
                                            @foreach($records as $date => $record)
                                                <td class="text-center">
                                                    @if($record)
                                                        @if($record->type_attendance == 1 && $record->extra_hours)
                                                            <span class="badge bg-success">P+</span>
                                                        @elseif($record->type_attendance == 1)
                                                            <span class="badge bg-success">P</span>
                                                        @elseif($record->type_attendance == 2)
                                                            <span class="badge bg-danger">A</span>
                                                        @else
                                                            <span class="badge bg-warning">AP</span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endforeach
                                            
                                            <td class="bg-primary text-white">{{ $presentCount }}</td>
                                            <td class="bg-primary text-white">{{ number_format($salary, 0) }}</td>
                                        </tr>
                                        
                                        <!-- Overtime Row -->
                                        <tr>
                                            <td>Overtime</td>
                                            @foreach($records as $date => $record)
                                                <td class="text-center">
                                                    @if($record)
                                                        {{ $record->extra_hours }}
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="bg-primary text-white">{{ $overtimeTotal }}</td>
                                            <td class="bg-primary text-white">{{ number_format($overtimeTotal * 62.5, 0) }}</td>
                                        </tr>
                                        
                                        <!-- Trip Row -->
                                        <tr>
                                            <td>Trip</td>
                                            @foreach($records as $date => $record)
                                                <td class="text-center">
                                                    @if($record)
                                                        {{ $record->driver_tuck_trip }}
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="bg-primary text-white">{{ $tripTotal }}</td>
                                            <td class="bg-primary text-white">0</td>
                                        </tr>
                                        
                                        <!-- Total Row -->
                                        <tr>
                                            <td>Total</td>
                                            @foreach($records as $date => $record)
                                                <td class="text-center">
                                                    @php
                                                        $attendanceValue = '-';
                                                        $overtimeValue = 0;
                                                        $tripValue = 0;
                                                        
                                                        if($record) {
                                                            if($record->type_attendance == 1) {
                                                                $attendanceValue = 'P';
                                                            } elseif($record->type_attendance == 2) {
                                                                $attendanceValue = 'A';
                                                            } else {
                                                                $attendanceValue = 'AP';
                                                            }
                                                            $overtimeValue = $record->extra_hours;
                                                            $tripValue = $record->driver_tuck_trip;
                                                        }
                                                        
                                                        $totalValue = 0;
                                                        if($attendanceValue == 'P') {
                                                            $totalValue = 1;
                                                        } elseif($attendanceValue == 'AP') {
                                                            $totalValue = 1;
                                                        }
                                                    @endphp
                                                    {{ $totalValue }}
                                                </td>
                                            @endforeach
                                            <td class="bg-primary text-white">Total</td>
                                            <td class="bg-primary text-white">{{ number_format($salary + ($overtimeTotal * 62.5), 0) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="{{ $daysInMonth + 3 }}" class="text-center">
                                                No attendance records found for the selected criteria.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Attendance Modal -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAttendanceModalLabel">Add Attendance Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAttendanceForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="add_date" name="date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_employee_id" class="form-label">Select Employee *</label>
                                <select id="add_employee_id" name="employee_id" class="form-select" required>
                                    <option value="">Select Employee</option>
                                    @foreach($allEmployees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_type_attendance" class="form-label">Attendance Type *</label>
                                <select id="add_type_attendance" name="type_attendance" class="form-select" required>
                                    <option value="1">Present</option>
                                    <option value="2">Absent</option>
                                    <option value="3">Absent (Paid)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="extra-hours-container">
                            <div class="mb-3">
                                <label for="add_extra_hours" class="form-label">Extra Working Hours</label>
                                <input type="number" class="form-control" id="add_extra_hours" name="extra_hours" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6" id="trips-container">
                            <div class="mb-3">
                                <label for="add_driver_tuck_trip" class="form-label">Trips</label>
                                <input type="number" class="form-control" id="add_driver_tuck_trip" name="driver_tuck_trip" min="0" value="0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveAttendanceBtn">Save Attendance</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Set today's date as default for add form
        const today = new Date().toISOString().split('T')[0];
        $('#add_date').val(today);
        
        // Print functionality
        $('#printCalendar').click(function() {
            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const month = urlParams.get('month') || '{{ $month }}';
            const year = urlParams.get('year') || '{{ $year }}';
            const userId = urlParams.get('user_id') || '{{ $userId ?? "" }}';
            
            // Construct the print URL
            let printUrl = "{{ route('attendance.print') }}?month=" + month + "&year=" + year;
            if (userId) {
                printUrl += "&user_id=" + userId;
            }
            
            // Open print window
            window.open(printUrl, '_blank');
        });
        
        // Save attendance
        $('#saveAttendanceBtn').click(function() {
            const formData = $('#addAttendanceForm').serialize();
            
            $.ajax({
                url: "{{ route('attendance.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#addAttendanceModal').modal('hide');
                        $('#addAttendanceForm')[0].reset();
                        $('#add_date').val(today);
                        
                        // Reload the page to show updated data
                        location.reload();
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            });
        });
        
        // Update month/year filter when changed
        $('#month_year').change(function() {
            const date = new Date($(this).val());
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            
            // Update URL parameters
            const url = new URL(window.location);
            url.searchParams.set('month', month);
            url.searchParams.set('year', year);
            
            // Preserve user filter if selected
            const userId = $('#user_id').val();
            if (userId) {
                url.searchParams.set('user_id', userId);
            }
            
            window.location.href = url;
        });
        
        // Update when user filter changes
        $('#user_id').change(function() {
            $('#attendanceCalendarForm').submit();
        });
        
        // Handle attendance type change to show/hide extra fields
        $('#add_type_attendance').change(function() {
            const attendanceType = $(this).val();
            // Hide extra fields for Absent (2) and Absent (Paid) (3)
            if (attendanceType == '2' || attendanceType == '3') {
                $('#extra-hours-container').hide();
                $('#trips-container').hide();
            } else {
                $('#extra-hours-container').show();
                $('#trips-container').show();
            }
        });
        
        // Trigger change event on page load to set initial state
        $('#add_type_attendance').trigger('change');
    });

    // Start Time End Time Countdown Timer
    $(document).ready(function () {

        function liveCountdown() {

            const today = new Date();
            const dateString = today.toISOString().split('T')[0]; // yyyy-mm-dd

            const endString = $("#endTime").text().trim();   // example: 18:00:00
            const endTime = new Date(dateString + " " + endString);
            const now = new Date();

            let diff = Math.floor((endTime - now) / 1000); // seconds

            if (diff < 0) diff = 0; // countdown stops at zero

            // Convert to HH:MM:SS
            const hours   = String(Math.floor(diff / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
            const seconds = String(diff % 60).padStart(2, '0');

            $("#startTimeEndTimeCountdown").text(
                `${hours}:${minutes}:${seconds}`
            );
            //when endtime == now then reload page
            if (diff === 1) {
                setTimeout(function() {
                    location.reload();
                }, 4000);
            }
        }

        liveCountdown();
        setInterval(liveCountdown, 1000); // Update every second

    });


</script>
@endpush