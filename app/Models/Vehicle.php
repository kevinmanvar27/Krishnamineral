<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = ['name', 'vehicle_name', 'vehicle_tare_weight', 'contact_number'];
}
