<?php

namespace App\Notifications;

use App\Models\MonthlyReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The monthly report instance.
     *
     * @var \App\Models\MonthlyReport
     */
    public $report;

    /**
     * The notification message.
     *
     * @var string
     */
    public $message;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\MonthlyReport  $report
     * @param  string  $message
     * @return void
     */
    public function __construct(MonthlyReport $report, string $message)
    {
        $this->report = $report;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $statusLabel = [
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ][$this->report->status] ?? $this->report->status;

        return (new MailMessage)
            ->subject('Status Laporan Bulanan Diperbarui - ' . $statusLabel)
            ->line($this->message)
            ->line('Periode: ' . \Carbon\Carbon::create($this->report->year, $this->report->month, 1)->translatedFormat('F Y'))
            ->line('Status: ' . $statusLabel)
            ->when($this->report->feedback, function ($message) {
                $message->line('Feedback: ' . $this->report->feedback);
            })
            ->action('Lihat Laporan', route('mahasiswa.reports.show', $this->report))
            ->line('Terima kasih telah menggunakan sistem kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'title' => 'Status Laporan Bulanan Diperbarui',
            'message' => $this->message,
            'url' => route('mahasiswa.reports.show', $this->report),
            'type' => 'report_status_updated',
        ];
    }
}
