<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Vehicle extends Model
{
    use LogsActivity;
    
    protected $fillable = ['name', 'vehicle_name', 'vehicle_tare_weight', 'contact_number', 'table_type'];
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Vehicle {$eventName}");
    }
}