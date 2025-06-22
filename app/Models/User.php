<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\StudentDocument;
use App\Models\Notification;
use App\Models\UsersReport;
use App\Models\MonthlyReport;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    // Role constants
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MAHASISWA = 'mahasiswa';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Scope a query to only include users with a specific role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }
    
    /**
     * Check if user has a specific role.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    /**
     * Check if the user can generate a certificate.
     *
     * @return bool
     */
    public function canGenerateCertificate()
    {
        // Pastikan user adalah mahasiswa
        if (!$this->isMahasiswa()) {
            return false;
        }
        
        // Pastikan memiliki aplikasi yang sudah diterima atau selesai
        $hasValidApplication = $this->applications()
            ->whereIn('status', ['diterima', 'selesai'])
            ->exists();
            
        return $hasValidApplication;
    }
    
    /**
     * Get all of the user's notifications.
     * Override method from Notifiable trait to use custom Notification model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    /**
     * Get the user's unread notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }
    
    /**
     * Get the user's read notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function readNotifications()
    {
        return $this->notifications()->whereNotNull('read_at');
    }
    
    /**
     * Send a custom notification to the user.
     *
     * @param  array  $data
     * @return \App\Models\Notification
     */
    public function sendCustomNotification(array $data)
    {
        return $this->notify([
            'title' => $data['title'] ?? 'Notifikasi Baru',
            'message' => $data['message'] ?? '',
            'type' => $data['type'] ?? 'info',
            'action_url' => $data['action_url'] ?? null,
            'data' => $data['data'] ?? [],
        ]);
    }
    
    /**
     * Get the monthly reports for the user.
     */
    public function monthlyReports()
    {
        return $this->hasMany(MonthlyReport::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is a student.
     */
    public function isMahasiswa(): bool
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    /**
     * Get the activities performed by this user (if admin).
     */
    public function activities(): HasMany
    {
        return $this->hasMany(AdminActivity::class, 'admin_id');
    }

    /**
     * Get the applications reviewed by this user (if admin).
     */
    public function reviewedApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'reviewed_by');
    }

    /**
     * Get the monthly reports reviewed by this user (if admin).
     */
    public function reviewedMonthlyReports(): HasMany
    {
        return $this->hasMany(MonthlyReport::class, 'reviewed_by');
    }

    /**
     * Get the student profile (if user is a student).
     */
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }
    
    /**
     * Get all certificates for the user.
     */
    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }
    
    /**
     * Get all applications for the user.
     */
    public function applications()
    {
        return $this->hasMany(\App\Models\Application::class);
    }

    /**
     * Get the user's reports.
    */
    public function reports()
    {
        return $this->hasMany(UsersReport::class, 'user_id');
    }
    
    /**
     * Check if the user has an active internship
     *
     * @return bool
     */
    public function hasActiveInternship()
    {
        return $this->applications()
            ->where('status', 'diterima')
            ->whereIn('status_magang', ['menunggu', 'diterima', 'in_progress'])
            ->whereHas('internship', function($query) {
                $query->where('end_date', '>=', now());
            })
            ->exists();
    }
    
    /**
     * Get the user's active internship application
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function activeInternship()
    {
        return $this->hasOne(Application::class)
            ->where('status', 'diterima')
            ->whereIn('status_magang', ['menunggu', 'diterima', 'in_progress'])
            ->whereHas('internship', function($query) {
                $query->where('end_date', '>=', now());
            })
            ->latest();
    }

    /**
     * Kirim notifikasi terkait lamaran magang
     *
     * @param string $type Jenis notifikasi (terkirim, diproses, diterima, ditolak)
     * @param \App\Models\Application $application
     * @param string|null $customMessage Pesan kustom (opsional)
     * @return \App\Models\Notification
     */
    public function sendApplicationNotification($type, Application $application, $customMessage = null)
    {
        // Definisikan template notifikasi berdasarkan jenis notifikasi
        $templates = [
            'terkirim' => [
                'title' => 'Lamaran Terkirim',
                'message' => 'Lamaran Anda untuk ' . ($application->internship->title ?? 'magang') . ' telah berhasil dikirim.',
                'type' => 'info',
                'notification_type' => 'application_submitted'
            ],
            'diproses' => [
                'title' => 'Lamaran Diproses',
                'message' => 'Lamaran Anda untuk ' . ($application->internship->title ?? 'magang') . ' sedang dalam proses review.',
                'type' => 'info',
                'notification_type' => 'application_processed'
            ],
            'diterima' => [
                'title' => 'Lamaran Diterima',
                'message' => 'Selamat! Lamaran Anda untuk ' . ($application->internship->title ?? 'magang') . ' telah diterima.',
                'type' => 'success',
                'notification_type' => 'application_accepted'
            ],
            'ditolak' => [
                'title' => 'Lamaran Ditolak',
                'message' => 'Maaf, lamaran Anda untuk ' . ($application->internship->title ?? 'magang') . ' tidak dapat diproses lebih lanjut.' . 
                             ($application->rejection_reason ? ' Alasan: ' . $application->rejection_reason : ''),
                'type' => 'error',
                'notification_type' => 'application_rejected'
            ]
        ];

        $template = $templates[$type] ?? [
            'title' => 'Pembaruan Status Lamaran',
            'message' => $customMessage ?? 'Ada pembaruan status lamaran Anda.',
            'type' => 'info',
            'notification_type' => 'application_updated'
        ];

        // Pastikan action URL valid
        // Gunakan rute dengan prefix 'mahasiswa.' untuk notifikasi ke mahasiswa
        $actionUrl = route('mahasiswa.applications.show', $application->id);
        
        // Siapkan data tambahan untuk disimpan di kolom data
        $notificationData = [
            'application_id' => $application->id,
            'internship_id' => $application->internship_id ?? null,
            'internship_title' => $application->internship->title ?? 'Magang',
            'status' => $type,
            'timestamp' => now()->toDateTimeString()
        ];

        try {
            // Buat notifikasi
            $notification = new \App\Models\Notification([
                'user_id' => $this->id,
                'title' => $template['title'],
                'message' => $template['message'],
                'is_read' => false,
                'type' => $template['notification_type'],
                'action_url' => $actionUrl,
                'data' => $notificationData,
                'read_at' => null,
                'notifiable_type' => get_class($application),
                'notifiable_id' => $application->id
            ]);
            
            $notification->save();

            // Dispatch event untuk update realtime
            if (class_exists('App\\Events\\NotificationCreated')) {
                event(new \App\Events\NotificationCreated($notification));
            }
            
            return $notification;
            
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim notifikasi: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}
