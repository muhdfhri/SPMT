<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'about_me',
        'full_name',
        'nik',
        'birth_place',
        'birth_date',
        'address',
        'is_personal_complete',
        'is_academic_complete',
        'is_family_complete',
        'is_documents_complete',
        'profile_completion_percentage',
        'profile_photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_personal_complete' => 'boolean',
        'is_academic_complete' => 'boolean',
        'is_family_complete' => 'boolean',
        'is_documents_complete' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(StudentEducation::class);
    }

    public function experiences()
    {
        return $this->hasMany(StudentExperience::class);
    }

    public function awards()
    {
        return $this->hasMany(StudentAward::class);
    }

    public function skills()
    {
        return $this->hasMany(StudentSkill::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(StudentFamilyMember::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function internshipApplications()
    {
        return $this->hasMany(InternshipApplication::class);
    }

    public function internshipReports()
    {
        return $this->hasMany(InternshipReport::class);
    }

    public function calculateCompletionPercentage()
    {
        $total = 4;
        $completed = 0;

        // Personal information (25%)
        $personalFields = ['full_name', 'nik', 'birth_place', 'birth_date', 'address'];
        $personalComplete = true;
        foreach ($personalFields as $field) {
            if (empty($this->$field)) {
                $personalComplete = false;
                break;
            }
        }
        $this->is_personal_complete = $personalComplete;
        if ($personalComplete) $completed++;

        // Academic (25%) - at least one of educations, experiences, or skills
        $academicComplete = (
            $this->educations->count() > 0 ||
            $this->experiences->count() > 0 ||
            $this->skills->count() > 0
        );
        $this->is_academic_complete = $academicComplete;
        if ($academicComplete) $completed++;

        // Family (25%) - at least one family member
        $familyComplete = $this->familyMembers->count() > 0;
        $this->is_family_complete = $familyComplete;
        if ($familyComplete) $completed++;

        // Documents (25%) - at least one document
        $documentsComplete = $this->documents->count() > 0;
        $this->is_documents_complete = $documentsComplete;
        if ($documentsComplete) $completed++;

        $this->profile_completion_percentage = round(($completed / $total) * 100);
        $this->save();

        return [
            'percentage' => $this->profile_completion_percentage,
            'is_personal_complete' => $this->is_personal_complete,
            'is_academic_complete' => $this->is_academic_complete,
            'is_family_complete' => $this->is_family_complete,
            'is_documents_complete' => $this->is_documents_complete,
        ];
    }

    public function isProfileComplete()
    {
        return $this->is_personal_complete && 
               $this->is_academic_complete && 
               $this->is_family_complete && 
               $this->is_documents_complete;
    }
}