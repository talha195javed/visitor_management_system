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
        'client_id',
        'package_type',
        'billing_cycle',
        'payment_intent_id',
        'stripe_customer_id',
        'payment_method_id',
        'amount',
        'currency',
        'status',
        'ip_address',
        'start_date',
        'end_date',
        'auto_renew',
    ];

    protected $casts = [
        'deleted_at',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function subscriptions()
    {
        return $this->hasMany(CustomerSubscription::class, 'client_id');
    }
}
