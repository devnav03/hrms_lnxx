<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LeaveApprovalSent extends Model {
	
    protected $fillable = [ 'user_id', 'leave_id', 'status', 'created_at', 'updated_at' ];


}

 