<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EidProcesse extends Model {

    protected $table = 'eid_processes';
    
    protected $fillable = [
        'candidate_id', 'pro_id', 'candidate_name', 'pro_email', 'comments', 'request_send_by', 'created_at', 'updated_at'
    ];

}
