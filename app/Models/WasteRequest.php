<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteRequest extends Model
{
    protected $fillable = [
        'user_id',
        'waste_category_id',
        'address',
        'latitude',
        'longitude',
        'status',
        'image_proof',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wasteCategory()
    {
        return $this->belongsTo(WasteCategory::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }
}
