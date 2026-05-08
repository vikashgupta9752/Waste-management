<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'driver_id',
        'waste_request_id',
        'status',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function wasteRequest()
    {
        return $this->belongsTo(WasteRequest::class);
    }
}
