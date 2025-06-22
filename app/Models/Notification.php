<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'is_read',
        'type',
        'action_url',
        'data',
        'read_at',
        'notifiable_type',
        'notifiable_id'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_read', 'title', 'message', 'action_url'];
    
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['notifiable', 'user'];
    

    
    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the title attribute.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        if (isset($this->attributes['title'])) {
            return $this->attributes['title'];
        }
        return $this->data['title'] ?? 'Notifikasi Baru';
    }
    
    /**
     * Get the message attribute.
     *
     * @return string
     */
    public function getMessageAttribute()
    {
        if (isset($this->attributes['message'])) {
            return $this->attributes['message'];
        }
        return $this->data['message'] ?? '';
    }
    
    /**
     * Get the action URL attribute.
     *
     * @return string|null
     */
    public function getActionUrlAttribute()
    {
        if (isset($this->attributes['action_url'])) {
            return $this->attributes['action_url'];
        }
        return $this->data['action_url'] ?? null;
    }
    
    /**
     * Get the notification type.
     *
     * @return string
     */
    public function getNotificationTypeAttribute()
    {
        if (isset($this->attributes['type'])) {
            return $this->attributes['type'];
        }
        return $this->data['type'] ?? 'info';
    }
    
    /**
     * Get the is_read attribute.
     *
     * @return bool
     */
    public function getIsReadAttribute()
    {
        if (isset($this->attributes['is_read'])) {
            return (bool) $this->attributes['is_read'];
        }
        return $this->read();
    }

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * Mark the notification as read.
     *
     * @return bool
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->forceFill(['read_at' => $this->freshTimestamp()])->save();
            return true;
        }
        return false;
    }

    /**
     * Mark the notification as unread.
     *
     * @return bool
     */
    public function markAsUnread()
    {
        if (!is_null($this->read_at)) {
            $this->forceFill(['read_at' => null])->save();
            return true;
        }
        return false;
    }

    /**
     * Determine if the notification has been read.
     *
     * @return bool
     */
    public function read()
    {
        return $this->read_at !== null;
    }

    /**
     * Determine if the notification has not been read.
     *
     * @return bool
     */
    public function unread()
    {
        return $this->read_at === null;
    }



    /**
     * Scope a query to only include read notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope a query to only include unread notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include notifications for the authenticated user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $user = null)
    {
        $user = $user ?? Auth::user();
        return $query->where('notifiable_type', get_class($user))
                    ->where('notifiable_id', $user->id);
    }
}
