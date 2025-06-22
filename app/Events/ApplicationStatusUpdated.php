<?php

namespace App\Events;

use App\Models\Application;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdated
{
    use Dispatchable, SerializesModels;

    public $application;
    public $oldStatus;
    public $newStatus;

    public function __construct(Application $application, $oldStatus, $newStatus)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;

        // Log informasi event
        \Log::info('=== ApplicationStatusUpdated EVENT DIBUAT ===', [
            'application_id' => $application->id,
            'internship_id' => $application->internship_id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'quota_sekarang' => $application->internship ? $application->internship->quota : null,
            'waktu' => now()->toDateTimeString()
        ]);
    }
}
