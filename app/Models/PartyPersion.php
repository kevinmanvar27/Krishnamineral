<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartyPersion extends Model
{
    protected $table = 'party_persions';
    protected $fillable = [
        'party_id',
        'persions',
        'persion_contact_number',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
}
