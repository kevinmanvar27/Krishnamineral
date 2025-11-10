<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Blasting extends Model
{
    use LogsActivity;
    
    protected $primaryKey = 'blasting_id';
    public $timestamps = true;
    
    protected $fillable = [
        'bnm_id',
        'b_notes',
        'date_time',
        'geliten',
        'geliten_rate',
        'geliten_total',
        'df',
        'df_rate',
        'df_total',
        'odvat',
        'od_rate',
        'od_total',
        'controll',
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
            ->setDescriptionForEvent(fn(string $eventName) => "Blasting record {$eventName}");
    }
    
    // Relationship with BlasterName
    public function blasterName()
    {
        return $this->belongsTo(BlasterName::class, 'bnm_id', 'bnm_id');
    }
    
    // Accessor for controll attribute to decode JSON
    public function getControllAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
    
    // Mutator for controll attribute to encode JSON
    public function setControllAttribute($value)
    {
        $this->attributes['controll'] = json_encode($value);
    }
}