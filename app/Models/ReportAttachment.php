<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAttachment extends Model
{
    use HasFactory;

    protected $table = 'report_attachments';
    protected $fillable = [
        'users_report_id', 'file_path', 'original_filename', 'mime_type', 'file_size'
    ];

    public function report()
    {
        return $this->belongsTo(UsersReport::class, 'users_report_id');
    }
} 