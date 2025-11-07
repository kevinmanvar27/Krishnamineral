<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all employees
        $employees = User::all();
        
        if ($employees->isEmpty()) {
            return;
        }
        
        // Generate attendance data for the current month
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Attendance types: 1 = present, 2 = absent, 3 = absent paid
        $attendanceTypes = [1, 2, 3];
        
        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            // Skip weekends (Saturday = 6, Sunday = 0)
            if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
                continue;
            }
            
            foreach ($employees as $employee) {
                // Randomly decide if we should create an attendance record
                if (rand(0, 100) > 20) { // 80% chance to create a record
                    Attendance::create([
                        'employee_id' => $employee->id,
                        'type_attendance' => $attendanceTypes[array_rand($attendanceTypes)],
                        'extra_hours' => rand(0, 8),
                        'driver_tuck_trip' => rand(0, 5),
                        'date' => $date->format('Y-m-d'),
                        'status' => 1,
                        'cron_jobs' => 0
                    ]);
                }
            }
        }
    }
}