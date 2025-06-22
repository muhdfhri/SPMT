<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Lamaran Dikirim',
            'message' => 'Lamaran Anda untuk ' . $this->application->internship->title . ' berhasil dikirim.',
            'url' => route('mahasiswa.applications.show', $this->application->id),
            'type' => 'application_submitted'
        ];
    }
}
