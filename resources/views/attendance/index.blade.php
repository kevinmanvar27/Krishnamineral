@extends('layouts.app')

@section('content')
<style>
.attendance-summary-table {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.attendance-calendar-table {
    table-layout: fixed;
    width: 100%;
    border-collapse: separate;
    border-spacing: 8px;
}

.attendance-calendar-table th {
    width: 14.28%; /* 100% / 7 days */
    background: #0d6efd;
    color: white;
    text-align: center;
    font-weight: bold;
    padding: 15px;
    font-size: 18px;
    border-radius: 8px;
}

.calendar-day-cell {
    height: 180px;
    min-height: 180px;
    vertical-align: top;
    background: white;
    padding: 12px;
    position: relative;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    width: 14.28%; /* 100% / 7 days */
}

.calendar-day-cell:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.calendar-day-cell.disabled {
    background: #e9ecef;
    color: #6c757d;
    border: 2px solid #ced4da;
}

.day-header {
    font-weight: bold;
    font-size: 20px;
    padding: 8px;
    border-bottom: 2px solid #dee2e6;
    margin-bottom: 12px;
    background: #f1f1f1;
    text-align: center;
    border-radius: 6px;
}

.attendance-counts {
    padding: 8px;
}

.count {
    margin-bottom: 10px;
    font-size: 16px;
    padding: 10px;
    border-radius: 6px;
    font-weight: 600;
    border: 2px solid transparent;
}

.present-highlight {
    background-color: #d1e7dd;
    border-color: #198754;
}

.present-highlight .label {
    color: #198754;
    font-weight: bold;
    font-size: 16px;
}

.present-highlight .value {
    color: #198754;
    font-weight: bold;
    float: right;
    font-size: 18px;
}

.absent-highlight {
    background-color: #f8d7da;
    border-color: #dc3545;
}

.absent-highlight .label {
    color: #dc3545;
    font-weight: bold;
    font-size: 16px;
}

.absent-highlight .value {
    color: #dc3545;
    font-weight: bold;
    float: right;
    font-size: 18px;
}

.paid-leave-highlight {
    background-color: #cce5ff;
    border-color: #0d6efd;
}

.paid-leave-highlight .label {
    color: #0d6efd;
    font-weight: bold;
    font-size: 16px;
}

.paid-leave-highlight .value {
    color: #0d6efd;
    font-weight: bold;
    float: right;
    font-size: 18px;
}

.no-data {
    text-align: center;
    color: #6c757d;
    font-style: italic;
    padding: 20px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: calc(100% - 50px);
}

.summary-stat {
    text-align: center;
    padding: 15px;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: transform 0.2s ease;
}

.summary-stat:hover {
    transform: translateY(-3px);
}

.summary-stat h3 {
    margin: 0;
    font-size: 2rem;
    color: #0d6efd;
}

.summary-stat p {
    margin: 5px 0 0;
    color: #6c757d;
    font-weight: 500;
}

.table-bordered td, .table-bordered th {
    border: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .calendar-day-cell {
        height: 150px;
        min-height: 150px;
        padding: 8px;
    }
    
    .day-header {
        font-size: 16px;
        padding: 5px;
    }
    
    .count {
        font-size: 14px;
        padding: 8px;
    }
    
    .count .value {
        font-size: 16px;
    }
    
    .attendance-calendar-table th {
        padding: 10px;
        font-size: 16px;
    }
    
    .no-data {
        font-size: 14px;
    }
}
</style>
<div class="wrapper">
    <div class="page-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Attendance Summary Calendar</h5>
                            <div class="card-header-action">
                                <a href="{{ route('attendance.calendar') }}" class="btn btn-secondary">
                                    <i class="lni lni-tablet"></i> Detailed View
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Filter Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <form id="attendanceSummaryForm" method="GET" action="{{ route('attendance.index') }}">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label for="month_year" class="form-label">Month & Year</label>
                                                <input type="month" class="form-control" id="month_year" name="month_year" 
                                                       value="{{ sprintf('%04d-%02d', $year, $month) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="lni lni-funnel"></i> Filter
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Hidden inputs for month and year to ensure proper parameter passing -->
                                        <input type="hidden" name="month" value="{{ $month }}">
                                        <input type="hidden" name="year" value="{{ $year }}">
                                    </form>
                                </div>
                            </div>

                            <!-- Calendar Table Visualization -->
                            <div class="attendance-summary-table">
                                <div class="calendar-header text-center mb-4">
                                    <h4>{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h4>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered attendance-calendar-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Sun</th>
                                                <th class="text-center">Mon</th>
                                                <th class="text-center">Tue</th>
                                                <th class="text-center">Wed</th>
                                                <th class="text-center">Thu</th>
                                                <th class="text-center">Fri</th>
                                                <th class="text-center">Sat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                                $firstDayOfMonth = date('w', strtotime("$year-$month-01"));
                                                $dayCounter = 1;
                                            @endphp
                                            
                                            <!-- Generate calendar rows -->
                                            @for ($week = 0; $week < 6; $week++)
                                                <tr>
                                                    @for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                                                        @php
                                                            $dayIndex = ($week * 7) + $dayOfWeek - $firstDayOfMonth + 1;
                                                            $isCurrentMonth = ($dayIndex > 0 && $dayIndex <= $daysInMonth);
                                                            $date = $isCurrentMonth ? sprintf('%04d-%02d-%02d', $year, $month, $dayIndex) : '';
                                                            $attendanceData = $isCurrentMonth && isset($attendanceSummary[$date]) ? $attendanceSummary[$date] : null;
                                                        @endphp
                                                        
                                                        @if ($isCurrentMonth)
                                                            <td class="calendar-day-cell">
                                                                <div class="day-header">{{ $dayIndex }}</div>
                                                                @if ($attendanceData)
                                                                    <div class="attendance-counts">
                                                                        <div class="count present-highlight" data-date="{{ $date }}" data-type="present">
                                                                            <span class="label">Present:</span>
                                                                            <span class="value">{{ $attendanceData['present'] }}</span>
                                                                        </div>
                                                                        <div class="count absent-highlight" data-date="{{ $date }}" data-type="absent">
                                                                            <span class="label">Absent:</span>
                                                                            <span class="value">{{ $attendanceData['absent'] }}</span>
                                                                        </div>
                                                                        @if ($attendanceData['paid_leave'] > 0)
                                                                        <div class="count paid-leave-highlight" data-date="{{ $date }}" data-type="paid_leave">
                                                                            <span class="label">Paid Leave:</span>
                                                                            <span class="value">{{ $attendanceData['paid_leave'] }}</span>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            @php $dayCounter++; @endphp
                                                        @else
                                                            <td class="calendar-day-cell" style="visibility: hidden;">
                                                                <div class="day-header">&nbsp;</div>
                                                            </td>
                                                        @endif
                                                    @endfor
                                                </tr>
                                                
                                                <!-- Break if we've displayed all days of the month -->
                                                @if ($dayCounter > $daysInMonth)
                                                    @break
                                                @endif
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Summary Statistics -->
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Attendance Summary</h5>
                                            <div class="row">
                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="summary-stat">
                                                        <h3>{{ $totalPresent }}</h3>
                                                        <p class="mb-0">Total Present</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="summary-stat">
                                                        <h3>{{ $totalAbsent }}</h3>
                                                        <p class="mb-0">Total Absent</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="summary-stat">
                                                        <h3>{{ $totalPaidLeave }}</h3>
                                                        <p class="mb-0">Paid Leave</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="summary-stat">
                                                        <h3>{{ $totalEmployees }}</h3>
                                                        <p class="mb-0">Total Employees</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Attendance Details Modal -->
<div class="modal fade" id="attendanceDetailsModal" tabindex="-1" aria-labelledby="attendanceDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceDetailsModalLabel">Attendance Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="attendance-details-content">
                    <!-- Content will be loaded here via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Update month/year filter when changed
    $('#month_year').change(function() {
        var selectedDate = $(this).val();
        if (selectedDate) {
            var parts = selectedDate.split('-');
            var year = parts[0];
            var month = parts[1];
            
            // Update hidden inputs
            $('input[name="month"]').val(month);
            $('input[name="year"]').val(year);
            
            // Submit the form
            $('#attendanceSummaryForm').submit();
        }
    });
    
    // Add hover effect to calendar cells
    $('.calendar-day-cell').hover(
        function() {
            $(this).css('cursor', 'pointer');
        }, function() {
            $(this).css('cursor', 'default');
        }
    );
    
    // Add click handlers for attendance count sections
    $(document).on('click', '.present-highlight, .absent-highlight, .paid-leave-highlight', function() {
        var date = $(this).data('date');
        var type = $(this).data('type');
        
        // Show loading in modal
        var typeLabel = type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        $('#attendanceDetailsModalLabel').text('Attendance Details - ' + typeLabel + ' (' + date + ')');
        $('#attendance-details-content').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        $('#attendanceDetailsModal').modal('show');
        
        // Fetch attendance details
        $.ajax({
            url: '{{ route("attendance.details") }}',
            method: 'GET',
            data: {
                date: date,
                type: type
            },
            success: function(response) {
                if (response.success) {
                    var html = '<div class="table-responsive">' +
                               '<table class="table table-striped">' +
                               '<thead>' +
                               '<tr>' +
                               '<th>Employee Name</th>' +
                               '<th>Employee ID</th>' +
                               '<th>Extra Hours</th>' +
                               '<th>Driver Tuck Trip</th>' +
                               '</tr>' +
                               '</thead>' +
                               '<tbody>';
                    
                    if (response.data.length > 0) {
                        $.each(response.data, function(index, attendance) {
                            html += '<tr>' +
                                    '<td>' + (attendance.employee ? attendance.employee.name : 'N/A') + '</td>' +
                                    '<td>' + (attendance.employee ? attendance.employee.id : 'N/A') + '</td>' +
                                    '<td>' + (attendance.extra_hours || 0) + '</td>' +
                                    '<td>' + (attendance.driver_tuck_trip || 0) + '</td>' +
                                    '</tr>';
                        });
                    } else {
                        html += '<tr><td colspan="4" class="text-center">No records found</td></tr>';
                    }
                    
                    html += '</tbody></table></div>';
                    $('#attendance-details-content').html(html);
                } else {
                    $('#attendance-details-content').html('<div class="alert alert-danger">Error loading data: ' + response.message + '</div>');
                }
            },
            error: function(xhr, status, error) {
                $('#attendance-details-content').html('<div class="alert alert-danger">Error loading data: ' + error + '</div>');
            }
        });
    });
});
</script>
@endpush