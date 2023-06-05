<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VanderStaff extends Model
{
    protected $table = 'vander_staffs';
    
    protected $fillable = [
        'name', 'email', 'mobile', 'status', 'vander_id', 'created_at', 'updated_at'
    ];

}
