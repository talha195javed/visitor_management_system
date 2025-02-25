<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'company', 'position', 'email', 'contact_number'];

    // Enables soft deletes

    protected $dates = ['deleted_at']; // Stores the soft delete timestamp

    public function visitors()
    {
        return $this->hasMany(VisitorsEmployer::class, 'employee_id');
    }


}
