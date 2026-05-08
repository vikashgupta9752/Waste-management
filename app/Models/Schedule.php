<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['waste_request_id', 'pickup_date', 'time_slot'];

    protected $casts = [
        'pickup_date' => 'date',
    ];

    public function wasteRequest()
    {
        return $this->belongsTo(WasteRequest::class);
    }
}
