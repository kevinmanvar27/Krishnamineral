<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = [
        'date_time',
        'vehicle_id',
        'transporter',
        'tare_weight',
        'contact_number'
    ];
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }


}
