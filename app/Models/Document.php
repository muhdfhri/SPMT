<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'type',
        'description',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size'
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
} 