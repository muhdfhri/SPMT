<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_report_id',
        'file_path',
        'original_filename',
        'file_size',
        'mime_type',
        'description',
    ];

    public function report()
    {
        return $this->belongsTo(InternshipReport::class, 'internship_report_id');
    }
} 