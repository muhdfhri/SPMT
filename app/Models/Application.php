<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Report;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Events\ApplicationStatusUpdated;

class Application extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'internship_id',
        'status',
        'status_magang',
        'notes',
        'approved_by',
        'rejected_by',
        'processed_by',
        'approved_at',
        'rejected_at',
        'processed_at',
        'rejection_reason',
    ];
    
    // Konstanta status magang
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_DITERIMA = 'diterima';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_BERJALAN = 'in_progress';
    const STATUS_SELESAI = 'completed';
    
    // Helper methods
    public function isInProgress()
    {
        return $this->status_magang === self::STATUS_BERJALAN;
    }

    public function isCompleted()
    {
        return $this->status_magang === self::STATUS_SELESAI;
    }
    
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updated(function ($application) {
            // Hanya proses jika status_magang berubah
            if ($application->isDirty('status_magang')) {
                $oldStatus = $application->getOriginal('status_magang');
                $newStatus = $application->status_magang;
                
                // Hanya lanjutkan jika ada perubahan status yang sebenarnya
                if ($oldStatus !== $newStatus) {
                    // Pastikan model sudah di-refresh untuk mendapatkan data terbaru
                    $application->refresh();
                    
                    // Debug log
                    \Log::info('Status magang berubah', [
                        'application_id' => $application->id,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                        'internship_id' => $application->internship_id,
                        'waktu' => now()->toDateTimeString()
                    ]);
                    
                    // Kirim event untuk SEMUA perubahan status
                    $application->withoutEvents(function () use ($application, $oldStatus, $newStatus) {
                        event(new ApplicationStatusUpdated(
                            $application,
                            $oldStatus,
                            $newStatus
                        ));
                    });
                } else {
                    \Log::warning('Percobaan update status_magang tanpa perubahan yang sebenarnya', [
                        'application_id' => $application->id,
                        'status' => $newStatus,
                        'waktu' => now()->toDateTimeString()
                    ]);
                }
            }
        });
    }
    
    /**
     * Get the user who processed the application.
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the user who approved the application.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who rejected the application.
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the internship that owns the application.
     */
    public function internship()
{
    return $this->belongsTo(Internship::class, 'internship_id');
}
    
    protected $dates = [
        'approved_at',
        'rejected_at',
        'processed_at',
    ];
    
    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'processed_at' => 'datetime',
        'status_magang' => 'string',
    ];
    
    /**
     * Get the options for the activity log.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'processed_by', 'processed_at', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('application');
    }
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Set default status to 'terkirim' if not set
            if (empty($model->status)) {
                $model->status = 'terkirim';
            }
            
            // Handle status changes
            if ($model->isDirty('status')) {
                $model->handleStatusChange($model->getOriginal('status'), $model->status);
            }
        });
    }
    
    /**
     * Handle the status change of the application
     *
     * @param string|null $oldStatus
     * @param string $newStatus
     * @return void
     */
    protected function handleStatusChange($oldStatus, $newStatus)
    {
        $user = auth()->user();
        
        // If status is being approved
        if ($newStatus === 'diterima' && $oldStatus !== 'diterima') {
            $this->approved_by = $user ? $user->id : null;
            $this->approved_at = now();
            $this->rejected_by = null;
            $this->rejected_at = null;
            $this->rejection_reason = null;
        }
        // If status is being rejected
        elseif ($newStatus === 'ditolak' && $oldStatus !== 'ditolak') {
            $this->rejected_by = $user ? $user->id : null;
            $this->rejected_at = now();
            $this->approved_by = null;
            $this->approved_at = null;
        }
        // If status is being reset to terkirim
        elseif ($newStatus === 'terkirim' && $oldStatus !== 'terkirim') {
            $this->approved_by = null;
            $this->approved_at = null;
            $this->rejected_by = null;
            $this->rejected_at = null;
            $this->rejection_reason = null;
        }
    }

    /**
     * Get the user that owns the application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the internship associated with the application.
     */


    /**
     * Get the admin who approved the application.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the admin who rejected the application.
     */
    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the reports for the application.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    
    /**
     * Get the monthly reports for the application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function monthlyReports()
    {
        return $this->hasMany(MonthlyReport::class, 'application_id');
    }

    /**
     * Get the certificate associated with the application.
     */
    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
    
    /**
     * Check if all monthly reports are completed and approved
     *
     * @return bool
     */
    /**
     * Check if all required monthly reports are completed and approved
     *
     * @return bool
     */
    /**
     * Calculate the expected number of months for the internship
     *
     * @return int
     */
    public function calculateExpectedMonths()
    {
        if (!$this->relationLoaded('internship')) {
            $this->load('internship');
        }
        
        if (!$this->internship) {
            return 0;
        }
        
        $startDate = \Carbon\Carbon::parse($this->internship->start_date);
        $endDate = \Carbon\Carbon::parse($this->internship->end_date);
        $monthsDiff = $startDate->floatDiffInMonths($endDate);
        
        return max(1, ceil($monthsDiff));
    }
    
    /**
     * Check if all required monthly reports are completed and approved
     *
     * @return bool
     */
    public function hasCompletedAllReports()
    {
        try {
            // Pastikan relasi dimuat
            if (!$this->relationLoaded('internship')) {
                $this->load('internship');
            }
            
            if (!$this->internship) {
                \Log::error('No internship found for application: ' . $this->id);
                return false;
            }
            
            // Pastikan relasi monthlyReports dimuat
            if (!$this->relationLoaded('monthlyReports')) {
                $this->load('monthlyReports');
            }
            
            // Hitung durasi magang dalam bulan dengan pembulatan ke atas
            $startDate = \Carbon\Carbon::parse($this->internship->start_date);
            $endDate = \Carbon\Carbon::parse($this->internship->end_date);
            
            // Hitung selisih bulan dengan float untuk akurasi
            $monthsDiff = $startDate->floatDiffInMonths($endDate);
            
            // Bulatkan ke atas untuk memastikan semua periode tercakup
            $expectedMonths = ceil($monthsDiff);
            
            // Pastikan minimal 1 bulan
            $expectedMonths = max(1, $expectedMonths);
            
            // Hitung laporan yang sudah disetujui
            $approvedReportsCount = $this->monthlyReports()
                ->where('status', 'approved')
                ->count();
            
            // Log informasi untuk debugging
            \Log::info('Report Completion Check:', [
                'application_id' => $this->id,
                'internship_id' => $this->internship->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'months_diff' => $monthsDiff,
                'expected_months' => $expectedMonths,
                'approved_reports' => $approvedReportsCount,
                'all_reports' => $this->monthlyReports->map(function($r) {
                    return [
                        'id' => $r->id,
                        'status' => $r->status,
                        'month' => $r->month,
                        'year' => $r->year,
                        'created_at' => $r->created_at
                    ];
                }),
                'is_completed' => ($approvedReportsCount >= $expectedMonths)
            ]);
            
            return $approvedReportsCount >= $expectedMonths;
            
        } catch (\Exception $e) {
            \Log::error('Error in hasCompletedAllReports for application ' . $this->id . ': ' . $e->getMessage());
            return false;
        }
    }
    

    /**
     * Approve the application.
     */
    public function approve(User $admin, string $notes = null): void
    {
        $this->update([
            'status' => 'diterima',
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'rejected_by' => null,
            'rejected_at' => null,
            'rejection_reason' => null,
            'notes' => $notes ?? $this->notes,
        ]);

        // Log the activity
        AdminActivity::log(
            $admin,
            'application_approved',
            "Application #{$this->id} has been approved",
            $this
        );
    }

    /**
     * Reject the application.
     */
    public function reject(User $admin, string $reason): void
    {
        $this->update([
            'status' => 'ditolak',
            'rejected_by' => $admin->id,
            'rejected_at' => now(),
            'approved_by' => null,
            'approved_at' => null,
            'rejection_reason' => $reason,
        ]);

        // Log the activity
        AdminActivity::log(
            $admin,
            'application_rejected',
            "Application #{$this->id} has been rejected",
            $this
        );
    }
}