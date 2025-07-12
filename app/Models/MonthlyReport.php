<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MonthlyReportAttachment;
use App\Models\Application;

class MonthlyReport extends Model
{
    use HasFactory;

    // Status constants
    public const STATUS_BELUM_TERISI = 'belum_terisi';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public static $statuses = [
        self::STATUS_BELUM_TERISI => 'Belum Terisi',
        self::STATUS_SUBMITTED => 'Telah Dikirim',
        self::STATUS_PENDING => 'Menunggu Review',
        self::STATUS_APPROVED => 'Disetujui',
        self::STATUS_REJECTED => 'Ditolak',
    ];

    protected $fillable = [
        'user_id',
        'application_id',
        'month',
        'year',
        'tasks',
        'achievements',
        'challenges',
        'status',
        'reviewed_by',
        'reviewed_at',
        'feedback',
    ];

    protected $casts = [
        'tasks' => 'string',
        'achievements' => 'string',
        'challenges' => 'string',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the monthly report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the application that owns the monthly report.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the admin who reviewed the report.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    
    /**
     * Get all attachments for the monthly report.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(MonthlyReportAttachment::class);
    }

    /**
     * Approve the monthly report.
     */
    public function approve(User $admin, ?string $feedback = null): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'feedback' => $feedback,
        ]);

        // Log the activity
        AdminActivity::log(
            $admin,
            'report_approved',
            "Monthly report #{$this->id} has been approved",
            $this
        );
    }

    /**
     * Reject the monthly report.
     */
    public function reject(User $admin, string $feedback): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'feedback' => $feedback,
        ]);

        // Log the activity
        AdminActivity::log(
            $admin,
            'report_rejected',
            "Monthly report #{$this->id} has been rejected",
            $this,
            ['feedback' => $feedback]
        );
    }

    /**
     * Scope a query to only include pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include approved reports.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include rejected reports.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }
}