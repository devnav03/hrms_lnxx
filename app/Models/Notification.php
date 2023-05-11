<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
	
    protected $fillable = ['notication_type', 'employee_id', 'master_id', 'title', 'image', 'description', 'crone_status', 'status', 'created_by', 'created_at', 'updated_at', 'deleted_at'];
}

 

