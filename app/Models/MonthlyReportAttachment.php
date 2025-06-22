<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyReportAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_report_id',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size'
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Get the monthly report that owns the attachment.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(MonthlyReport::class, 'monthly_report_id');
    }

    /**
     * Get the file's URL for display.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
