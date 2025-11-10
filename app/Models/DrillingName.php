<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DrillingName extends Model
{
    use LogsActivity;
    
    protected $table = 'drilling_names';
    protected $primaryKey = 'dri_id';
    public $timestamps = true;
    
    protected $fillable = ['d_name', 'phone_no', 'status'];
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Drilling name {$eventName}");
    }
    
    // Accessor for status attribute
    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'active' : 'inactive';
    }
    
    // Mutator for status attribute
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value === 'active' ? 1 : 0;
    }
}