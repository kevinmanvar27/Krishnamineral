<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Attendance;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AttendanceUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and assign a role
        $this->user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);
        $this->user->assignRole($role);
    }

    /** @test */
    public function it_can_update_an_attendance_record_via_ajax()
    {
        // Create an attendance record
        $attendance = Attendance::factory()->create([
            'employee_id' => $this->user->id,
            'type_attendance' => 1, // Present
            'extra_hours' => 0,
            'driver_tuck_trip' => 0
        ]);

        // Login as the user
        $this->actingAs($this->user);

        // Send PUT request to update the attendance
        $response = $this->putJson("/attendance/{$attendance->id}", [
            'type_attendance' => 2, // Absent
            'extra_hours' => 2,
            'driver_tuck_trip' => 1
        ]);

        // Assert the response
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Attendance record updated successfully.'
        ]);

        // Assert the attendance record was updated in the database
        $this->assertDatabaseHas('attendances', [
            'id' => $attendance->id,
            'type_attendance' => 2,
            'extra_hours' => 2,
            'driver_tuck_trip' => 1
        ]);
    }
}