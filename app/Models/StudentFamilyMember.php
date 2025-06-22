<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'name',
        'relationship',
        'occupation',
        'phone_number',
        'address',
        'is_emergency_contact',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_emergency_contact' => 'boolean',
    ];

    /**
     * Get the student profile that owns the family member.
     */
    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}