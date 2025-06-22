<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'description',
        'requirements',
        'location',
        'start_date',
        'end_date',
        'application_deadline',
        'quota',
        'division',
        'education_qualification',
        'is_active',
        'type',
        'short_description'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'application_deadline' => 'date',
        'is_active' => 'boolean',
        'quota' => 'integer',
        'description' => 'string',
        'requirements' => 'string',
    ];
    
    /**
     * The attributes that should be treated as HTML.
     *
     * @var array
     */
    protected $htmlable = [
        'description',
        'requirements',
    ];
    
    protected $appends = ['education_qualification_label'];
    
    /**
     * Get the education qualification label
     *
     * @return string
     */
    public function getEducationQualificationLabelAttribute()
    {
        return [
            'SMA/SMK' => 'SMA/SMK',
            'Vokasi' => 'Vokasi (D1/D2/D3/D4)',
            'S1' => 'Sarjana (S1)',
        ][$this->education_qualification] ?? $this->education_qualification;
    }
    
    /**
     * Get the applications for the internship.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
    
    /**
     * Check if the internship is open for application
     *
     * @return bool
     */
    public function isOpenForApplication()
    {
        if (!$this->application_deadline) {
            return false;
        }
        
        // Menggunakan startOfDay() untuk memastikan perbandingan tanggal saja
        $today = now()->startOfDay();
        $deadline = $this->application_deadline->startOfDay();
        
        return $this->is_active && 
               $deadline->greaterThanOrEqualTo($today) &&
               $this->hasAvailableQuota();
    }
    
    /**
     * Check if the internship still has available quota
     *
     * @return bool
     */
    /**
     * Check if the internship still has available quota
     *
     * @return bool
     */
    public function hasAvailableQuota()
{
    $acceptedCount = $this->applications()
        ->where('status_magang', 'diterima')  // Diubah dari 'status' ke 'status_magang'
        ->count();
        
    return $acceptedCount < $this->quota;
}
    
    /**
     * Check if the user has already applied to this internship
     * 
     * @param int $userId
     * @return bool
     */
    public function hasApplied($userId)
    {
        return $this->applications()->where('user_id', $userId)->exists();
    }
    
    /**
     * Get the duration of the internship in months
     *
     * @return int
     */
    public function getDurationInMonths()
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }
        
        $start = $this->start_date->startOfDay();
        $end = $this->end_date->startOfDay();
        
        // Calculate the difference in months
        $diff = $start->diff($end);
        $months = $diff->y * 12 + $diff->m;
        
        // If the difference in days is more than 0, round up to the next month
        if ($diff->d > 0 || $months == 0) {
            $months++;
        }
        
        return $months > 0 ? $months : 1; // Minimum 1 month
    }

    /**
     * Get the certificates for this internship.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}