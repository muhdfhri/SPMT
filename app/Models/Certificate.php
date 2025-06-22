<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_GENERATED = 'generated';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REVOKED = 'revoked';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'internship_id',
        'application_id',
        'certificate_number',
        'certificate_path',
        'issue_date',
        'status',
        'verified_at',
        'verified_by',
        'revoked_at',
        'revoked_reason',
        'metadata'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'verified_at' => 'datetime',
        'revoked_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'issue_date',
        'verified_at',
        'revoked_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the internship that owns the certificate.
     */
    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    /**
     * Get the application associated with the certificate.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the admin who verified the certificate.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the status options for the certificate.
     *
     * @return array
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_GENERATED => 'Telah Digenerate',
            self::STATUS_PUBLISHED => 'Telah Diterbitkan',
            self::STATUS_REVOKED => 'Dicabut',
        ];
    }

    /**
     * Get all available statuses (alias for getStatusOptions)
     *
     * @return array
     */
    public static function getStatuses()
    {
        return self::getStatusOptions();
    }

    /**
     * Get the status badge HTML
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            self::STATUS_PUBLISHED => [
                'class' => 'bg-green-100 text-green-800',
                'label' => 'Diterbitkan'
            ],
            self::STATUS_REVOKED => [
                'class' => 'bg-red-100 text-red-800',
                'label' => 'Dibatalkan'
            ],
            self::STATUS_PENDING => [
                'class' => 'bg-yellow-100 text-yellow-800',
                'label' => 'Menunggu'
            ],
            self::STATUS_GENERATED => [
                'class' => 'bg-blue-100 text-blue-800',
                'label' => 'Telah Digenerate'
            ]
        ];

        $status = $statuses[$this->status] ?? [
            'class' => 'bg-gray-100 text-gray-800',
            'label' => $this->status
        ];

        return sprintf(
            '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full %s">%s</span>',
            $status['class'],
            $status['label']
        );
    }

    /**
     * Scope a query to only include published certificates.
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    /**
     * Get the URL to download the certificate.
     *
     * @return string|null
     */
    public function getDownloadUrl()
    {
        return $this->certificate_path ? Storage::url($this->certificate_path) : null;
    }

    /**
     * Check if the certificate is published.
     *
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Check if the certificate is revoked.
     *
     * @return bool
     */
    public function isRevoked()
    {
        return $this->status === self::STATUS_REVOKED;
    }

}