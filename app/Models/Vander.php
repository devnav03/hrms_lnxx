<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Vander extends Model
{
    protected $table = 'vanders';
    
    protected $fillable = [
        'name', 'address', 'status', 'created_at', 'updated_at'
    ];

}
