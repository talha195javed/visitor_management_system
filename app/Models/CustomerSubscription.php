<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'package_type',
        'billing_cycle',
        'payment_intent_id',
        'amount',
        'currency',
        'status',
        'ip_address',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'deleted_at',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'amount' => 'decimal:2'
    ];
}
