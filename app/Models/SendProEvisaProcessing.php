<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SendProEvisaProcessing extends Model
{
    protected $table = 'send_pro_evisa_processings';
    
    protected $fillable = [
        'candidate_id', 'pro_id', 'agency', 'created_by', 'comments', 'status', 'created_at', 'updated_at'
    ];

}
