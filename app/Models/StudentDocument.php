<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'type',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
        'description',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}