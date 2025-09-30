<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = [
        'name',
        'sales_by',
    ];

    public function items()
    {
        return $this->hasMany(PartyPersion::class);
    } 

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_by');
    }
}
