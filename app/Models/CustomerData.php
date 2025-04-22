<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerData extends Model
{
    use HasFactory;

    protected $table = 'customer_data';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'message',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];
}
