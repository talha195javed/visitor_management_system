<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenSetting extends Model
{
    use HasFactory;

    protected $fillable = ['screen_name', 'is_visible', 'name'];
}
