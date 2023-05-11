<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PushNotificationHistory extends Model {
	
    protected $fillable = ['user_id', 'notification_id', 'status', 'is_view', 'created_at', 'updated_at', 'deleted_at'];
}

 

