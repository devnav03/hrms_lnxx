<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ShiftOfDay extends Model {

    protected $table = 'shift_of_days';
    
    protected $fillable = [
        'shift_id', 'month', 'day', 'created_at', 'updated_at'
    ];

}
