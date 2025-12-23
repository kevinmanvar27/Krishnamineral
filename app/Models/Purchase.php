<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Purchase extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'date_time',
        'vehicle_id',
        'transporter',
        'tare_weight',
        'contact_number',
        'driver_contact_number',
        'gross_weight',
        'net_weight',
        'material_id',
        'loading_id',
        'quarry_id',
        'receiver_id',
        'driver_id',
        'carting_id',
        'note',
        'status'
    ];
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Purchase record {$eventName}");
    }
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function material()
    {
        return $this->belongsTo(Materials::class);
    }

    public function loading()
    {
        return $this->belongsTo(Loading::class);
    }

    public function quarry()
    {
        return $this->belongsTo(PurchaseQuarry::class);
    }

    public function receiver()
    {
        return $this->belongsTo(PurchaseReceiver::class);
    }

    public function royalty()
    {
        return $this->belongsTo(Royalty::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    
    /**
     * Get the driver, which can be either a Driver model or a User model
     * 
     * @return mixed
     */
    public function getAssignedDriver()
    {
        // If driver_id exists and is a driver from drivers table
        if ($this->driver_id && strpos($this->driver_id, 'driver_') === 0) {
            $driverId = str_replace('driver_', '', $this->driver_id);
            return Driver::find($driverId);
        }
        // If driver_id exists and is a user with driver role
        elseif ($this->driver_id && strpos($this->driver_id, 'user_') === 0) {
            $userId = str_replace('user_', '', $this->driver_id);
            return User::find($userId);
        }
        // Fallback to direct relationship for backward compatibility
        elseif ($this->driver_id) {
            return Driver::find($this->driver_id);
        }
        
        return null;
    }
    
    /**
     * Check if the assigned driver is a user with driver role
     * 
     * @return bool
     */
    public function hasUserDriver()
    {
        return $this->driver_id && strpos($this->driver_id, 'user_') === 0;
    }
    
    /**
     * Check if the assigned driver is from the drivers table
     * 
     * @return bool
     */
    public function hasTableDriver()
    {
        return $this->driver_id && (strpos($this->driver_id, 'driver_') === 0 || is_numeric($this->driver_id));
    }
}