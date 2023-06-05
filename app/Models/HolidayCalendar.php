<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HolidayCalendar extends Model
{
    protected $table = 'holiday_calendars';
    
    protected $fillable = [
        'name', 'year', 'day', 'month', 'status', 'created_by', 'created_at', 'updated_at'
    ];

}
