<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'internship_application_id',
        'title',
        'content',
        'report_date',
        'status',
        'feedback',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function application()
    {
        return $this->belongsTo(InternshipApplication::class, 'internship_application_id');
    }

    public function attachments()
    {
        return $this->hasMany(InternshipAttachment::class);
    }
} 