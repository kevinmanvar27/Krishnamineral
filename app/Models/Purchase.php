<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
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
    ];
    
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
}
