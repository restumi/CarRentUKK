<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'nik',
        'ktp_image',
        'face_image',
        'status',
        'reject_reason'
    ];

    protected $hidden = [
        'password'
    ];
}
