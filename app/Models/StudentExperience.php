<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'company_name',
        'position',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}