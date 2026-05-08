<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = ['area_name', 'predicted_value', 'prediction_date'];
}
