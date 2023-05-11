<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EidDocument extends Model
{
    protected $table = 'eid_documents';
    
    protected $fillable = [
        'candidate_id', 'title', 'file_name', 'created_by', 'created_at', 'updated_at'
    ];

}
