<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivity extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the admin that performed the activity.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the subject of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Log a new activity.
     */
    public static function log(
        User $admin,
        string $action,
        string $description,
        Model $subject,
        ?array $properties = null
    ): self {
        return static::create([
            'admin_id' => $admin->id,
            'action' => $action,
            'description' => $description,
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
            'properties' => $properties,
        ]);
    }
}
