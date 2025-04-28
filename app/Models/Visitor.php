<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visitor extends Model
{
    use HasFactory, SoftDeletes; // Combine trait usage

    protected $fillable = [
        'client_id',
        'full_name',
        'company',
        'email',
        'phone',
        'check_in_time',
        'check_out_time',
        'photo',
        'identification_number',
        'role',
        'id_photo',
        'id_type',
        'country_code',
    ];

    protected $dates = ['deleted_at']; // This is optional in Laravel 11+

    public function employers()
    {
        return $this->hasMany(VisitorsEmployer::class, 'visitor_id');
    }
}

