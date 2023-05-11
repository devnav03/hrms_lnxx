<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AttendanceHistory extends Model {
	
    protected $fillable = [ 'ams_data', 'last_synchronize_date', 'created_at', 'updated_at' ];


}

 