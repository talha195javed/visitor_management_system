<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorsEmployer extends Model
{
    use HasFactory;

    protected $table = 'visitors_employers';

    protected $fillable = [
        'visitor_id',
        'employee_id',
        'purpose'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
