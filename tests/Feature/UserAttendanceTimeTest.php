<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserAttendanceTimeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create necessary permissions and roles for testing
        Permission::create(['name' => 'add-attendance']);
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('add-attendance');
    }

    /**
     * Test that attendance time fields are properly stored in the database.
     */
    public function test_attendance_time_fields_are_stored_in_database()
    {
        Storage::fake('public');
        
        // Create a user to act as the authenticated user
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        // Create the add-attendance permission
        $attendancePermission = Permission::where('name', 'add-attendance')->first();
        
        // Prepare test data
        $testData = [
            'username' => 'Test User',
            'birthdate' => '1990-01-01',
            'contact_number_1' => '1234567890',
            'joining_date' => '2023-01-01',
            'shift_start_time' => '09:00',
            'shift_end_time' => '17:00',
            'employee_gender' => '1',
            'user_type' => $user->roles->first()->id,
            'department' => 'IT',
            'salary' => '50000',
            'bank' => '1',
            'court' => '1',
            'permissions' => [$attendancePermission->id],
            'attendance_start_time' => '08:00',
            'attendance_end_time' => '18:00',
            // Required file uploads
            'user_photo' => UploadedFile::fake()->image('user_photo.jpg'),
            'user_photo_id' => UploadedFile::fake()->image('user_photo_id.jpg'),
            'user_address_proof' => UploadedFile::fake()->image('user_address_proof.jpg'),
        ];

        // Submit the form
        $response = $this->post(route('users.store'), $testData);

        // Assert the user was created
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        // Check that the user was created in the database
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'attendance_start_time' => '08:00:00',
            'attendance_end_time' => '18:00:00',
        ]);

        // Get the created user
        $createdUser = User::where('name', 'Test User')->first();
        
        // Verify the attendance time fields are stored correctly
        $this->assertEquals('08:00:00', $createdUser->attendance_start_time);
        $this->assertEquals('18:00:00', $createdUser->attendance_end_time);
    }

    /**
     * Test that attendance time fields can be null when not provided.
     */
    public function test_attendance_time_fields_can_be_null()
    {
        Storage::fake('public');
        
        // Create a user to act as the authenticated user
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);

        // Create the add-attendance permission
        $attendancePermission = Permission::where('name', 'add-attendance')->first();
        
        // Prepare test data without attendance time fields
        $testData = [
            'username' => 'Test User 2',
            'birthdate' => '1990-01-01',
            'contact_number_1' => '1234567890',
            'joining_date' => '2023-01-01',
            'shift_start_time' => '09:00',
            'shift_end_time' => '17:00',
            'employee_gender' => '1',
            'user_type' => $user->roles->first()->id,
            'department' => 'IT',
            'salary' => '50000',
            'bank' => '1',
            'court' => '1',
            'permissions' => [$attendancePermission->id],
            // No attendance time fields provided
            // Required file uploads
            'user_photo' => UploadedFile::fake()->image('user_photo.jpg'),
            'user_photo_id' => UploadedFile::fake()->image('user_photo_id.jpg'),
            'user_address_proof' => UploadedFile::fake()->image('user_address_proof.jpg'),
        ];

        // Submit the form
        $response = $this->post(route('users.store'), $testData);

        // Assert the user was created
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        // Check that the user was created in the database with null attendance times
        $this->assertDatabaseHas('users', [
            'name' => 'Test User 2',
            'attendance_start_time' => null,
            'attendance_end_time' => null,
        ]);
    }
}