<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    protected $fillable = ['name', 'description', 'guidelines'];

    public function wasteRequests()
    {
        return $this->hasMany(WasteRequest::class);
    }
}
