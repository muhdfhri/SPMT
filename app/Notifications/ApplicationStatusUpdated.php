<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;
    protected $application;
    protected $url;

    public function __construct($status, $application, $url)
    {
        $this->status = $status;
        $this->application = $application;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Status Lamaran Diperbarui')
            ->line("Status lamaran Anda untuk {$this->application->internship->title} telah diperbarui menjadi " . ucfirst($this->status))
            ->action('Lihat Detail', $this->url);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Status Lamaran Diperbarui',
            'message' => "Status lamaran Anda untuk {$this->application->internship->title} telah diperbarui menjadi " . ucfirst($this->status),
            'url' => $this->url,
            'type' => 'application_updated'
        ];
    }
}
