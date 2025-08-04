<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'plate_number',
        'price_per_day',
        'description',
        'image',
    ];
}
