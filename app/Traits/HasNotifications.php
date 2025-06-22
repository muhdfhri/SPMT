<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasNotifications
{
    /**
     * Get all of the model's notifications.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * Create a new notification for this model.
     */
    public function notify(User $user, string $title, string $message, string $type = 'info', ?string $actionUrl = null, ?array $data = null): Notification
    {
        return $user->notifications()->create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'action_url' => $actionUrl,
            'data' => $data,
            'notifiable_type' => get_class($this),
            'notifiable_id' => $this->id,
        ]);
    }
}
