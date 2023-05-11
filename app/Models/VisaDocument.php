<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VisaDocument extends Model
{
    protected $table = 'visa_documents';
    
    protected $fillable = [
        'candidate_id', 'title', 'file_name', 'created_by', 'created_at', 'updated_at'
    ];

}
