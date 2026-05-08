<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationLog extends Model
{
    protected $fillable = ['event', 'details'];

    protected $casts = [
        'details' => 'array'
    ];
}
