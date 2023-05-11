<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SignedLcMolDoc extends Model
{
    protected $table = 'signed_lc_mol_docs';
    
    protected $fillable = [
        'candidate_id', 'title', 'file_name', 'created_by', 'created_at', 'updated_at'
    ];

}
