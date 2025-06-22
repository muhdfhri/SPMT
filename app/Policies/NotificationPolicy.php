<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotificationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user yang terautentikasi bisa melihat notifikasinya sendiri
    }

    /**
     * Determine whether the user can view the notification.
     */
    public function view(User $user, Notification $notification): bool
    {
        return $this->isOwner($user, $notification);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Notifikasi dibuat oleh sistem
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Notification $notification): bool
    {
        return $this->isOwner($user, $notification);
    }

    /**
     * Determine whether the user can delete the notification.
     */
    public function delete(User $user, Notification $notification): bool
    {
        return $this->isOwner($user, $notification);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Notification $notification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Notification $notification): bool
    {
        return false;
    }

    /**
     * Determine whether the user can mark the notification as read.
     */
    public function markAsRead(User $user, Notification $notification): bool
    {
        return $this->isOwner($user, $notification);
    }
    
    /**
     * Check if the user is the owner of the notification.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Notification  $notification
     * @return bool
     */
    protected function isOwner(User $user, Notification $notification): bool
    {
        return $user->id == $notification->notifiable_id && 
               $notification->notifiable_type === get_class($user);
    }

    /**
     * Determine whether the user can mark all notifications as read.
     */
    public function markAllAsRead(User $user): bool
    {
        return true; // User bisa menandai semua notifikasinya sebagai sudah dibaca
    }
}
