<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceiver extends Model
{
    protected $fillable = [
        'name',
        'contact_number',
        'sales_by',
        'table_type'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseReceiverPersion::class, 'receiver_id');
    } 

    public function salesPerson()
    {
        return $this->belongsTo(User::class, 'sales_by');
    }
}
