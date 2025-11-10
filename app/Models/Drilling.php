<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Drilling extends Model
{
    use LogsActivity;
    
    protected $primaryKey = 'drilling_id';
    public $timestamps = true;
    
    protected $fillable = [
        'dri_id',
        'd_notes',
        'date_time',
        'hole',
        'gross_total',
        'status',
        'update_by'
    ];
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Drilling record {$eventName}");
    }
    
    // Relationship with DrillingName
    public function drillingName()
    {
        return $this->belongsTo(DrillingName::class, 'dri_id', 'dri_id');
    }
    
    // Accessor for hole attribute to decode JSON
    public function getHoleAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
    
    // Mutator for hole attribute to encode JSON
    public function setHoleAttribute($value)
    {
        $this->attributes['hole'] = json_encode($value);
    }
}