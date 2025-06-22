<?php

namespace App\Observers;

use App\Models\MonthlyReport;
use App\Models\Application;

class MonthlyReportObserver
{
    /**
     * Handle the MonthlyReport "created" event.
     */
    public function created(MonthlyReport $monthlyReport): void
    {
        //
    }

    /**
     * Handle the MonthlyReport "updated" event.
     */
    public function updated(MonthlyReport $monthlyReport): void
    {
        // Log ketika laporan diperbarui
        if ($monthlyReport->isDirty('status') && $monthlyReport->status === 'approved') {
            $application = $monthlyReport->application;
            
            if ($application) {
                // Log bahwa laporan disetujui tapi tidak mengubah status magang
                \Log::info('Laporan disetujui, menunggu konfirmasi selesai dari admin', [
                    'report_id' => $monthlyReport->id,
                    'application_id' => $application->id,
                    'status_magang' => $application->status_magang
                ]);
            }
        }
    }

    /**
     * Handle the MonthlyReport "deleted" event.
     */
    public function deleted(MonthlyReport $monthlyReport): void
    {
        //
    }

    /**
     * Handle the MonthlyReport "restored" event.
     */
    public function restored(MonthlyReport $monthlyReport): void
    {
        //
    }

    /**
     * Handle the MonthlyReport "force deleted" event.
     */
    public function forceDeleted(MonthlyReport $monthlyReport): void
    {
        //
    }
}
