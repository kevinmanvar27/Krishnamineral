<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sales extends Model
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
        'party_weight',
        'material_id',
        'loading_id',
        'place_id',
        'party_id',
        'royalty_id',
        'royalty_number',
        'royalty_tone',
        'driver_id',
        'carting_id',
        'rate',
        'gst',
        'amount',
        'note',
        'status'
    ];
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Sales record {$eventName}");
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

    public function place()
    {
        return $this->belongsTo(Places::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function royalty()
    {
        return $this->belongsTo(Royalty::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}