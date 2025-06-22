<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'name',
        'proficiency_level',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}