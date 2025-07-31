<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'plate_number',
        'price_per_day',
        'description',
        'image',
    ];
}
