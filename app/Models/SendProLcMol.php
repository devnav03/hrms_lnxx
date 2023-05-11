<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SendProLcMol extends Model
{
    protected $table = 'send_pro_lc_mols';
    
    protected $fillable = [
        'candidate_id', 'pro_id', 'status', 'candidate_name', 'pro_email', 'comments', 'request_send_by', 'created_at', 'updated_at'
    ];

}
