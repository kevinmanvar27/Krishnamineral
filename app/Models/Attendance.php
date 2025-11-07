<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Attendance extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'employee_id',
        'type_attendance',
        'extra_hours',
        'driver_tuck_trip',
        'date',
        'status',
        'cron_jobs'
    ];

    protected $casts = [
        'date' => 'date'
    ];
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Attendance record {$eventName}");
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}