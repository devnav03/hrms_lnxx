<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SalaryGenerate extends Model
{
    protected $table = 'salary_generates';
    
    protected $fillable = [
        'user_id', 
        'month_year', 
        'net_salary', 
        'present', 
        'absent', 
        'leave', 
        'incentive', 
        'other_deduction',
        'bonus', 
        'earned_salary', 
        'deduction_salary',
        'status', 
        'created_at', 
        'updated_at', 
        'created_by'
    ];

}
