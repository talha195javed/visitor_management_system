<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
    ];

    public function getPriceAttribute()
    {
        return request()->has('annual') ? $this->yearly_price : $this->monthly_price;
    }

    public function getBillingPeriodAttribute()
    {
        return request()->has('annual') ? 'year' : 'month';
    }
}
