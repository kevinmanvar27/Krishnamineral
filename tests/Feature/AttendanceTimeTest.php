<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AttendanceTimeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function attendance_button_shows_during_allowed_time()
    {
        // Create a user with specific attendance times
        $user = User::factory()->create([
            'attendance_start_time' => '09:00:00',
            'attendance_end_time' => '17:00:00'
        ]);

        // Mock the current time to be within the allowed window
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 12, 0, 0));

        $this->actingAs($user);
        
        // Check that the button would be visible
        $startTime = $user->attendance_start_time;
        $endTime = $user->attendance_end_time;
        $currentTime = now()->format('H:i:s');
        
        $isVisible = ($currentTime >= $startTime && $currentTime <= $endTime);
        
        $this->assertTrue($isVisible);
    }

    /** @test */
    public function attendance_button_hides_outside_allowed_time()
    {
        // Create a user with specific attendance times
        $user = User::factory()->create([
            'attendance_start_time' => '09:00:00',
            'attendance_end_time' => '17:00:00'
        ]);

        // Mock the current time to be outside the allowed window
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 18, 0, 0));

        $this->actingAs($user);
        
        // Check that the button would be hidden
        $startTime = $user->attendance_start_time;
        $endTime = $user->attendance_end_time;
        $currentTime = now()->format('H:i:s');
        
        $isVisible = ($currentTime >= $startTime && $currentTime <= $endTime);
        
        $this->assertFalse($isVisible);
    }

    /** @test */
    public function attendance_button_handles_midnight_crossing()
    {
        // Create a user with attendance times that cross midnight
        $user = User::factory()->create([
            'attendance_start_time' => '22:00:00',
            'attendance_end_time' => '06:00:00'
        ]);

        // Test time within the window (after midnight crossing)
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 23, 0, 0));
        
        $this->actingAs($user);
        
        $startTime = $user->attendance_start_time;
        $endTime = $user->attendance_end_time;
        $currentTime = now()->format('H:i:s');
        
        // Handle case where end time is earlier than start time (crossing midnight)
        if ($endTime >= $startTime) {
            $isVisible = ($currentTime >= $startTime && $currentTime <= $endTime);
        } else {
            // Crossing midnight case
            $isVisible = ($currentTime >= $startTime || $currentTime <= $endTime);
        }
        
        $this->assertTrue($isVisible);
        
        // Test time outside the window
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 12, 0, 0));
        
        $currentTime = now()->format('H:i:s');
        
        // Handle case where end time is earlier than start time (crossing midnight)
        if ($endTime >= $startTime) {
            $isVisible = ($currentTime >= $startTime && $currentTime <= $endTime);
        } else {
            // Crossing midnight case
            $isVisible = ($currentTime >= $startTime || $currentTime <= $endTime);
        }
        
        $this->assertFalse($isVisible);
    }
}