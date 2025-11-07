<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Calendar - {{ date('M-Y', mktime(0, 0, 0, $month, 1, $year)) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            word-wrap: break-word;
            min-width: 15px;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .bg-primary {
            background-color: #007bff;
            color: white;
        }

        .text-white {
            color: white;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 0.1em 0.2em;
            font-size: 7px;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.15rem;
        }

        .bg-success {
            background-color: #28a745;
            color: white;
        }

        .bg-danger {
            background-color: #dc3545;
            color: white;
        }

        .bg-warning {
            background-color: #ffc107;
            color: black;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
        }

        .header p {
            margin: 3px 0;
            font-size: 10px;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 8px;
        }

        .signature-line {
            margin-top: 30px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 150px;
        }

        /* Landscape orientation for better fit */
        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        /* Reduce font size for day cells */
        .day-cell {
            font-size: 7px;
            padding: 1px;
        }
        
        /* Fixed width for header columns */
        .header-col {
            width: 3%;
        }
        
        .day-col {
            width: auto;
        }
        
        .total-col {
            width: 3%;
        }
        
        .salary-col {
            width: 4%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Attendance Calendar</h1>
        <p>Period: {{ date('M-Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
        @if(isset($currentUser))
        <p>Employee: {{ ucfirst($currentUser->name) ?? 'N/A' }}</p>
        @endif
    </div>

    @if(isset($employeeAttendances))
    <div class="table-responsive">
        <table class="table table-bordered attendance-calendar-table">
            <thead>
                <tr>
                    <th class="header-col">Date</th>
                    @php
                        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        $dayWidth = 90 / $daysInMonth;
                    @endphp
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        <th class="day-col" style="width: {{ $dayWidth }}%;">{{ sprintf('%02d', $day) }}</th>
                    @endfor
                    <th class="total-col">Total</th>
                    <th class="salary-col">Salary</th>
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
                        <td class="text-center day-cell">
                            @if($record)
                                @if($record->type_attendance == 1)
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
                        <td class="text-center day-cell">
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
                        <td class="text-center day-cell">
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
                        <td class="text-center day-cell">
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

    <div class="footer">
        <div class="signature-line">Authorized Signature</div>
    </div>
</body>
</html>