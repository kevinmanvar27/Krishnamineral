<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceiverPersion extends Model
{
    protected $table = 'purchase_receiver_persions';
    protected $fillable = [
        'receiver_id',
        'persions',
        'persion_contact_number',
    ];
    
    public function party()
    {
        return $this->belongsTo(PurchaseReceiver::class, 'receiver_id');
    }
    
}
