<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Party extends Model
{
    use LogsActivity;
    
    protected $fillable = [
        'name',
        'contact_number',
        'sales_by',
        'table_type'
    ];
    
    // Configure activity logging
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Party {$eventName}");
    }
    
    public function items()
    {
        return $this->hasMany(PartyPersion::class);
    } 

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_by');
    }
}