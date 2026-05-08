<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'driver_id',
        'plate_number',
        'current_location_lat',
        'current_location_lng',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
