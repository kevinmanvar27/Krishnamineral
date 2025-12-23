<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Handle month_year parameter (YYYY-MM format) or fallback to separate month/year parameters
        $monthYear = $request->get('month_year');
        if ($monthYear) {
            $dateParts = explode('-', $monthYear);
            $year = $dateParts[0];
            $month = $dateParts[1];
        } else {
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
        }
        
        // Get attendance records for the selected month/year
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $attendances = Attendance::whereBetween('date', [$startDate, $endDate])->get();
        
        // Group attendances by date and calculate present/absent counts
        $attendanceSummary = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalPaidLeave = 0;
        
        foreach ($attendances as $attendance) {
            $date = $attendance->date->format('Y-m-d');
            
            if (!isset($attendanceSummary[$date])) {
                $attendanceSummary[$date] = [
                    'present' => 0,
                    'absent' => 0,
                    'paid_leave' => 0
                ];
            }
            
            switch ($attendance->type_attendance) {
                case 1: // Present
                    $attendanceSummary[$date]['present']++;
                    $totalPresent++;
                    break;
                case 2: // Absent
                    $attendanceSummary[$date]['absent']++;
                    $totalAbsent++;
                    break;
                case 3: // Absent (Paid)
                    $attendanceSummary[$date]['paid_leave']++;
                    $totalPaidLeave++;
                    break;
            }
        }
        
        // Get total employees count
        $totalEmployees = User::count();
        
        return view('attendance.index', compact('attendanceSummary', 'month', 'year', 'totalPresent', 'totalAbsent', 'totalPaidLeave', 'totalEmployees'));
    }

    public function calendar(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        $userId = $request->get('user_id', null);
        
        // If no user_id is specified, get the first user by default
        if (!$userId) {
            $firstUser = User::orderBy('name')->first();
            $userId = $firstUser ? $firstUser->id : null;
        }
        
        // Get employees - if user_id is specified, get only that user, otherwise get first user
        if ($userId) {
            $employees = User::where('id', $userId)->orderBy('name')->get();
        } else {
            $employees = User::orderBy('name')->get();
        }
        
        // Get the current user (first user in the list or the filtered user)
        $currentUser = $employees->first();
        
        // Get all employees for the filter dropdown
        $allEmployees = User::orderBy('name')->get();
        
        // Get roles for user type mapping
        $roles = Role::pluck('name', 'id')->all();
        
        // Get attendance records for the selected month/year
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $attendancesQuery = Attendance::whereBetween('date', [$startDate, $endDate]);
        
        // If user_id is specified, filter attendances for that user only
        if ($userId) {
            $attendancesQuery->where('employee_id', $userId);
        }
        
        $attendances = $attendancesQuery->get();
        
        // Group attendances by employee
        $employeeAttendances = [];
        foreach ($employees as $employee) {
            $employeeAttendances[$employee->id] = [
                'employee' => $employee,
                'records' => []
            ];
            
            // Initialize records for each day of the month
            $daysInMonth = $startDate->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                $employeeAttendances[$employee->id]['records'][$date] = null;
            }
            
            // Fill in actual attendance records
            $employeeRecords = $attendances->where('employee_id', $employee->id);
            foreach ($employeeRecords as $record) {
                $employeeAttendances[$employee->id]['records'][$record->date->format('Y-m-d')] = $record;
            }
        }
        
        return view('attendance.calendar', compact('employees', 'employeeAttendances', 'month', 'year', 'allEmployees', 'userId', 'currentUser', 'roles'));
    }

    public function print(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        $userId = $request->get('user_id', null);
        
        // If no user_id is specified, get the first user by default
        if (!$userId) {
            $firstUser = User::orderBy('name')->first();
            $userId = $firstUser ? $firstUser->id : null;
        }
        
        // Get employees - if user_id is specified, get only that user, otherwise get first user
        if ($userId) {
            $employees = User::where('id', $userId)->orderBy('name')->get();
        } else {
            $employees = User::orderBy('name')->get();
        }
        
        // Get the current user (first user in the list or the filtered user)
        $currentUser = $employees->first();
        
        // Get all employees for the filter dropdown
        $allEmployees = User::orderBy('name')->get();
        
        // Get roles for user type mapping
        $roles = Role::pluck('name', 'id')->all();
        
        // Get attendance records for the selected month/year
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $attendancesQuery = Attendance::whereBetween('date', [$startDate, $endDate]);
        
        // If user_id is specified, filter attendances for that user only
        if ($userId) {
            $attendancesQuery->where('employee_id', $userId);
        }
        
        $attendances = $attendancesQuery->get();
        
        // Group attendances by employee
        $employeeAttendances = [];
        foreach ($employees as $employee) {
            $employeeAttendances[$employee->id] = [
                'employee' => $employee,
                'records' => []
            ];
            
            // Initialize records for each day of the month
            $daysInMonth = $startDate->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                $employeeAttendances[$employee->id]['records'][$date] = null;
            }
            
            // Fill in actual attendance records
            $employeeRecords = $attendances->where('employee_id', $employee->id);
            foreach ($employeeRecords as $record) {
                $employeeAttendances[$employee->id]['records'][$record->date->format('Y-m-d')] = $record;
            }
        }
        
        // Load the PDF view with the same data
        $pdf = Pdf::loadView('attendance.pdf', compact('employees', 'employeeAttendances', 'month', 'year', 'allEmployees', 'userId', 'currentUser', 'roles', 'startDate', 'endDate'));
        
        // Return the PDF as a download
        return $pdf->download('Attendance_Calendar_' . $year . '_' . sprintf('%02d', $month) . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'type_attendance' => 'required|in:1,2,3',
            'extra_hours' => 'nullable|integer|min:0',
            'driver_tuck_trip' => 'nullable|integer|min:0'
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $request->employee_id,
                'date' => $request->date
            ],
            [
                'type_attendance' => $request->type_attendance,
                'extra_hours' => $request->extra_hours ?? 0,
                'driver_tuck_trip' => $request->driver_tuck_trip ?? 0,
                'status' => 1
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Attendance record saved successfully.'
        ]);
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'type_attendance' => 'required|in:1,2,3',
            'extra_hours' => 'nullable|integer|min:0',
            'driver_tuck_trip' => 'nullable|integer|min:0'
        ]);

        // Only update the fields that can be changed from the calendar view
        $attendance->update([
            'type_attendance' => $request->type_attendance,
            'extra_hours' => $request->extra_hours ?? 0,
            'driver_tuck_trip' => $request->driver_tuck_trip ?? 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance record updated successfully.'
        ]);
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance record deleted successfully.'
        ]);
    }

    /**
     * Get detailed attendance data for a specific date and type
     */
    public function getAttendanceDetails(Request $request)
    {
        $date = $request->get('date');
        $type = $request->get('type');
        
        // Validate input
        if (!$date || !$type) {
            return response()->json(['error' => 'Date and type are required'], 400);
        }
        
        // Map type to attendance type
        $typeMap = [
            'present' => 1,
            'absent' => 2,
            'paid_leave' => 3
        ];
        
        $typeAttendance = $typeMap[$type] ?? null;
        if (!$typeAttendance) {
            return response()->json(['error' => 'Invalid type'], 400);
        }
        
        // Get attendance records for the date and type
        $attendances = Attendance::where('date', $date)
            ->where('type_attendance', $typeAttendance)
            ->with('employee')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $attendances,
            'date' => $date,
            'type' => $type
        ]);
    }

    /**
     * Check if an employee exists in either sales or purchase modules
     */
    public function checkEmployeeStatus(Request $request)
    {
        $employeeId = $request->get('employee_id');
        
        // Validate input
        if (!$employeeId) {
            return response()->json(['error' => 'Employee ID is required'], 400);
        }
        
        // Get the employee (user)
        $employee = \App\Models\User::find($employeeId);
        
        if (!$employee) {
            return response()->json(['is_active' => false]);
        }
        
        // Check if employee is a driver by role name
        $driverRole = Role::where('name', 'driver')->first();
        if (!$driverRole || $employee->user_type != $driverRole->id) {
            return response()->json(['is_active' => false]);
        }
        
        // Find a driver with the same name as the employee
        $driver = \App\Models\Driver::where('name', $employee->name)->first();
        
        // Also check for a driver entry linked to this user
        $userLinkedDriver = \App\Models\Driver::where('user_id', $employee->id)->first();
        
        if (!$driver && !$userLinkedDriver) {
            return response()->json(['is_active' => false]);
        }
        
        // Check if driver exists in sales module
        $existsInSales = false;
        if ($driver) {
            $existsInSales = \App\Models\Sales::where('driver_id', $driver->id)->exists();
        }
        
        // Check if driver exists in purchase module
        $existsInPurchase = false;
        if ($driver) {
            $existsInPurchase = \App\Models\Purchase::where('driver_id', $driver->id)->exists();
        }
        
        // Also check for user-linked driver in sales and purchase modules
        if ($userLinkedDriver) {
            $existsInSales = $existsInSales || \App\Models\Sales::where('driver_id', $userLinkedDriver->id)->exists();
            $existsInPurchase = $existsInPurchase || \App\Models\Purchase::where('driver_id', $userLinkedDriver->id)->exists();
        }
        
        // Employee is active if the corresponding driver exists in either sales or purchase
        $isActive = $existsInSales || $existsInPurchase;
        
        // Determine which driver object to return
        $driverToReturn = $driver ?: $userLinkedDriver;
        
        return response()->json([
            'is_active' => $isActive,
            'driver_id' => $driverToReturn ? $driverToReturn->id : null
        ]);
    }
}