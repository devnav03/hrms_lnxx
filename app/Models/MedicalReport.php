<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MedicalReport extends Model
{
    protected $table = 'medical_reports';
    
    protected $fillable = [
        'candidate_id', 'title', 'file_name', 'created_by', 'created_at', 'updated_at'
    ];

}
