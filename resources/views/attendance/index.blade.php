@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Attendance Records</h5>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal" id="openAddAttendanceModal">
                                    <i class="lni lni-plus"></i> Add Attendance
                                </button>
                            </div>
                        </div>  
                        <div class="card-body">
                            <!-- Filter Section -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <form id="attendanceFilterForm" method="GET" action="{{ route('attendance.index') }}">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label for="month" class="form-label">Month</label>
                                                <select id="month" name="month" class="form-select">
                                                    <option value="">Select Month</option>
                                                    @for($m = 1; $m <= 12; $m++)
                                                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="year" class="form-label">Year</label>
                                                <select id="year" name="year" class="form-select">
                                                    <option value="">Select Year</option>
                                                    @for($y = date('Y'); $y >= 2020; $y--)
                                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                                            {{ $y }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="employee_id" class="form-label">Employee</label>
                                                <select id="employee_id" name="employee_id" class="form-select js-select2">
                                                    <option value="">All Employees</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                                            {{ $employee->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="d-flex">
                                                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                                                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Summary Section -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">Attendance Summary</h6>
                                                <div>
                                                    <button type="button" class="btn btn-info btn-sm me-2" id="viewSummaryBtn">
                                                        Summary View
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-sm" id="viewCalendarBtn">
                                                        Calendar View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Attendance Table -->
                            <div class="table-responsive">
                                <table id="attendanceTable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="border-b">
                                            <th>Date</th>
                                            <th>Employee ID</th>
                                            <th>Employee Name</th>
                                            <th>Designation</th>
                                            <th>Attendance</th>
                                            <th>Overtime (Hours)</th>
                                            <th>Trips</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($attendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->date->format('d M, Y') }}</td>
                                                <td>{{ $attendance->employee->id ?? 'N/A' }}</td>
                                                <td>{{ $attendance->employee->name ?? 'N/A' }}</td>
                                                <td>{{ strtoupper($attendance->employee->role ?? 'Employee') }}</td>
                                                <td>
                                                    @if($attendance->type_attendance == 1)
                                                        <span class="badge bg-success">Present</span>
                                                    @elseif($attendance->type_attendance == 2)
                                                        <span class="badge bg-danger">Absent</span>
                                                    @else
                                                        <span class="badge bg-warning">Absent (Paid)</span>
                                                    @endif
                                                </td>
                                                <td>{{ $attendance->extra_hours }}</td>
                                                <td>{{ $attendance->driver_tuck_trip }}</td>
                                                <td class="d-flex">
                                                    <button class="btn btn-info btn-sm me-2 edit-attendance" 
                                                            data-id="{{ $attendance->id }}"
                                                            data-employee-id="{{ $attendance->employee_id }}"
                                                            data-date="{{ $attendance->date->format('Y-m-d') }}"
                                                            data-type="{{ $attendance->type_attendance }}"
                                                            data-extra-hours="{{ $attendance->extra_hours }}"
                                                            data-driver-trip="{{ $attendance->driver_tuck_trip }}"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editAttendanceModal">
                                                        <i class="lni lni-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-danger btn-sm delete-attendance" 
                                                            data-id="{{ $attendance->id }}">
                                                        <i class="lni lni-trash-can"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $attendances->firstItem() ?? 0 }} to {{ $attendances->lastItem() ?? 0 }} of {{ $attendances->total() ?? 0 }} entries
                                </div>
                                <div>
                                    {{ $attendances->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [Attendance] end -->
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
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_extra_hours" class="form-label">Extra Working Hours</label>
                                <input type="number" class="form-control" id="add_extra_hours" name="extra_hours" min="0" value="0">
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
                        <div class="col-md-6">
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

<!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAttendanceModalLabel">Edit Attendance Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editAttendanceForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_attendance_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="edit_date" name="date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_employee_id" class="form-label">Select Employee *</label>
                                <select id="edit_employee_id" name="employee_id" class="form-select" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_extra_hours" class="form-label">Extra Working Hours</label>
                                <input type="number" class="form-control" id="edit_extra_hours" name="extra_hours" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_type_attendance" class="form-label">Attendance Type *</label>
                                <select id="edit_type_attendance" name="type_attendance" class="form-select" required>
                                    <option value="1">Present</option>
                                    <option value="2">Absent</option>
                                    <option value="3">Absent (Paid)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_driver_tuck_trip" class="form-label">Trips</label>
                                <input type="number" class="form-control" id="edit_driver_tuck_trip" name="driver_tuck_trip" min="0" value="0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateAttendanceBtn">Update Attendance</button>
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
                        
                        // Reload the table
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
        
        // Edit attendance
        $(document).on('click', '.edit-attendance', function() {
            const id = $(this).data('id');
            const employeeId = $(this).data('employee-id');
            const date = $(this).data('date');
            const type = $(this).data('type');
            const extraHours = $(this).data('extra-hours');
            const driverTrip = $(this).data('driver-trip');
            
            $('#edit_attendance_id').val(id);
            $('#edit_employee_id').val(employeeId);
            $('#edit_date').val(date);
            $('#edit_type_attendance').val(type);
            $('#edit_extra_hours').val(extraHours);
            $('#edit_driver_tuck_trip').val(driverTrip);
        });
        
        // Update attendance
        $('#updateAttendanceBtn').click(function() {
            const id = $('#edit_attendance_id').val();
            const formData = $('#editAttendanceForm').serialize();
            
            $.ajax({
                url: `/attendance/${id}`,
                type: "PUT",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#editAttendanceModal').modal('hide');
                        
                        // Reload the table
                        location.reload();
                        
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
        
        // Delete attendance
        $(document).on('click', '.delete-attendance', function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/attendance/${id}`,
                        type: "DELETE",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success) {
                                // Reload the table
                                location.reload();
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
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
                }
            });
        });
        
        // View summary
        $('#viewSummaryBtn').click(function() {
            const month = $('#month').val();
            const year = $('#year').val();
            
            if (!month || !year) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Please select both month and year to view summary.',
                });
                return;
            }
            
            window.location.href = `{{ route('attendance.index') }}?month=${month}&year=${year}`;
        });
        
        // View calendar
        $('#viewCalendarBtn').click(function() {
            const month = $('#month').val() || new Date().getMonth() + 1;
            const year = $('#year').val() || new Date().getFullYear();
            
            window.location.href = `{{ route('attendance.calendar') }}?month=${month}&year=${year}`;
        });
    });
</script>
@endpush