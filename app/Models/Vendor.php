<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'vendor_code',
        'vendor_name',
        'contact_person',
        'mobile',
        'telephone',
        'email_id',
        'website',
        'country',
        'state',
        'city',
        'pincode',
        'address',
        'bank_proof',
        'payment_conditions',
        'visiting_card',
        'note'
    ];
}