<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 
        'username',
        'email',
        'contact_number',
        'user_photo',
        'user_photo_id',
        'user_type',
        'department',
        'salary',
        'birthdate',
        'password',
        'contact_number_1',
        'contact_number_2',
        'joining_date',
        'user_address_proof',
        'employee_gender',
        'insurance',
        'insurance_name',
        'insurance_policy_copy',
        'insurance_issue_date',
        'insurance_valid_date',
        'nominee_name',
        'nominee_mobile_number',
        'nominee_photo_id',
        'nominee_address_proof',
        'nominee_gender',
        'nominee_birthdate',
        'insurance_note',
        'licence',
        'bank_proof',
        'bank',
        'court',
        'court_case_files',
        'court_case_close_file',
        'note'
    ];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
    ];

}