<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    // Define the table name (optional, if it matches the model's pluralized name)
    protected $table = 'company_info';

    // Define the fillable fields (columns you want to allow mass assignment for)
    protected $fillable = [
        'company_name',
        'company_email',
        'hr_email',
        'welcome_screen_image',
        'main_screen_image',
        'remaining_screen_image',
    ];

    // If you're using timestamps (created_at, updated_at), you can customize them like this:
    // public $timestamps = false; // Uncomment if you're not using timestamps
}
