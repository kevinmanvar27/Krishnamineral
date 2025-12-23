<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Driver extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'name',
        'driver',
        'contact_number',
        'table_type',
        'is_active',
        'last_active_at',
        'user_id',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'last_active_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns this driver entry (if it's linked to a user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Check if this driver entry is linked to a user
     */
    public function isUserLinked()
    {
        return !is_null($this->user_id);
    }
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Driver {$eventName}");
    }
    
    /**
     * Scope a query to only include active drivers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Check if driver is inactive for more than the configured threshold
     *
     * @return bool
     */
    public function isInactiveForMoreThanThreshold()
    {
        if (!$this->is_active || !$this->last_active_at) {
            return true;
        }
        
        // Get the threshold in minutes from the linked user, default to 1 minute if no user or no threshold
        $thresholdMinutes = 1;
        
        if ($this->user_id) {
            $user = User::find($this->user_id);
            if ($user && $user->work_timing_initiate_checking !== null) {
                $thresholdMinutes = $user->work_timing_initiate_checking;
            }
        }
        
        return $this->last_active_at->diffInMinutes(now()) > $thresholdMinutes;
    }
}