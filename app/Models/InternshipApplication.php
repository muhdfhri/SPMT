<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'company_name',
        'position',
        'description',
        'start_date',
        'end_date',
        'status',
        'rejection_reason',
        'supervisor_feedback',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function reports()
    {
        return $this->hasMany(InternshipReport::class);
    }
} 