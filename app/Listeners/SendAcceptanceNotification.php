<?php

namespace App\Listeners;

use App\Events\ApplicationStatusUpdated;
use App\Mail\InternshipAcceptanceNotification;
use App\Mail\InternshipRejectionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAcceptanceNotification
{
    public function handle(ApplicationStatusUpdated $event)
    {
        // Skip jika tidak ada perubahan status
        if ($event->oldStatus === $event->newStatus) {
            return;
        }

        try {
            // Handle status diterima
            if ($event->newStatus === 'diterima' && $event->oldStatus !== 'diterima') {
                $this->sendAcceptanceEmail($event);
            } 
            // Handle status ditolak
            elseif ($event->newStatus === 'ditolak' && $event->oldStatus !== 'ditolak') {
                $this->sendRejectionEmail($event);
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email notifikasi: ' . $e->getMessage(), [
                'application_id' => $event->application->id,
                'status' => $event->newStatus,
                'error' => $e->getTraceAsString()
            ]);
        }
    }

    protected function sendAcceptanceEmail($event)
    {
        Log::info('Mengirim email notifikasi penerimaan', [
            'email' => $event->application->user->email,
            'application_id' => $event->application->id
        ]);

        Mail::to($event->application->user->email)
           ->send(new InternshipAcceptanceNotification($event->application));
    }

    protected function sendRejectionEmail($event)
    {
        Log::info('Mengirim email notifikasi penolakan', [
            'email' => $event->application->user->email,
            'application_id' => $event->application->id,
            'rejection_reason' => $event->application->rejection_reason
        ]);

        Mail::to($event->application->user->email)
           ->send(new InternshipRejectionNotification($event->application));
    }
}
