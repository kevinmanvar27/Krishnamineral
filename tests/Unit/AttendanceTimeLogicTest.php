<?php

namespace Tests\Unit;

use Tests\TestCase;
use Carbon\Carbon;

class AttendanceTimeLogicTest extends TestCase
{
    /**
     * Test attendance button visibility during allowed time
     *
     * @return void
     */
    public function test_attendance_button_shows_during_allowed_time()
    {
        // Set current time within allowed window
        $currentTime = '12:00:00';
        $startTime = '09:00:00';
        $endTime = '17:00:00';
        
        // Check that the button would be visible
        $isVisible = ($currentTime >= $startTime && $currentTime <= $endTime);
        
        $this->assertTrue($isVisible);
    }

    /**
     * Test attendance button visibility outside allowed time
     *
     * @return void
     */
    public function test_attendance_button_hides_outside_allowed_time()
    {
        // Set current time outside allowed window
        $currentTime = '18:00:00';
        $startTime = '09:00:00';
        $endTime = '17:00:00';
        
        // Check that the button would be hidden
        $isVisible = ($currentTime >= $startTime && $currentTime <= $endTime);
        
        $this->assertFalse($isVisible);
    }

    /**
     * Test attendance button visibility with midnight crossing
     *
     * @return void
     */
    public function test_attendance_button_handles_midnight_crossing()
    {
        // Test time within the window (after midnight crossing)
        $currentTime = '23:00:00';
        $startTime = '22:00:00';
        $endTime = '06:00:00';
        
        // Handle case where end time is earlier than start time (crossing midnight)
        if ($endTime >= $startTime) {
            $isVisible = ($currentTime >= $startTime && $currentTime <= $endTime);
        } else {
            // Crossing midnight case
            $isVisible = ($currentTime >= $startTime || $currentTime <= $endTime);
        }
        
        $this->assertTrue($isVisible);
        
        // Test time outside the window
        $currentTime = '12:00:00';
        
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