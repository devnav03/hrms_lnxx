<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MedicalAppointment extends Model
{
    protected $table = 'medical_appointments';
    
    protected $fillable = [
        'candidate_id', 'appointment_time', 'comments', 'place', 'attachment', 'status', 'created_by', 'created_at', 'updated_at'
    ];

}
