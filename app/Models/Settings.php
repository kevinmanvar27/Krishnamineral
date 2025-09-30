<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'logo',
        'name',
        'tagline',
        'favicon',
        'gst_number',
        'address',
        'location',
        'contact_number',
        'whatsapp_number',
        'copyright',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'authorized_signatory',
    ];
}
