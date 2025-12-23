<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;
    
    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass()
    {
        return 'App\\Models\\User';
    }

    protected $fillable = [
        'name', 
        'username',
        'email',
        'contact_number',
        'user_photo',
        'user_photo_id',
        'user_type',
        'department',
        'salary',
        'birthdate',
        'password',
        'contact_number_1',
        'contact_number_2',
        'joining_date',
        'user_address_proof',
        'employee_gender',
        'insurance',
        'insurance_name',
        'insurance_policy_copy',
        'insurance_issue_date',
        'insurance_valid_date',
        'nominee_name',
        'nominee_mobile_number',
        'nominee_photo_id',
        'nominee_address_proof',
        'nominee_gender',
        'nominee_birthdate',
        'insurance_note',
        'licence',
        'bank_proof',
        'bank',
        'court',
        'court_case_files',
        'court_case_close_file',
        'note',
        'shift_start_time',
        'shift_end_time',
        'attendance_start_time',
        'attendance_end_time',
        'work_timing_enabled',
        'work_timing_initiate_checking',
        'task_start_time',
        'task_completed',
        'task_description',
        'last_activity_at'
    ];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'task_start_time' => 'datetime',
        'task_completed' => 'boolean',
    ];
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "User {$eventName}");
    }
}