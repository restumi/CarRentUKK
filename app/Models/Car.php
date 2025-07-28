<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'license_plate',
        'rental_price',
        'description',
        'image',
        'is_available',
    ];
}
