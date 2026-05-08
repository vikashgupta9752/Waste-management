<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = ['waste_request_id', 'code', 'image_path'];

    public function wasteRequest()
    {
        return $this->belongsTo(WasteRequest::class);
    }
}
