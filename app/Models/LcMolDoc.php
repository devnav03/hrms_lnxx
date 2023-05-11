<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LcMolDoc extends Model
{
    protected $table = 'lc_mol_docs';
    
    protected $fillable = [
        'candidate_id', 'title', 'file_name', 'created_by', 'created_at', 'updated_at'
    ];

}
