<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAward extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_profile_id',
        'title',
        'issuer',
        'date_received',
        'description',
    ];

    protected $casts = [
        'date_received' => 'date',
    ];

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}