<?php

namespace App\Console\Commands;

use App\Models\Application;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateInternshipStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'internship:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status magang secara otomatis berdasarkan tanggal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Update ke completed jika sudah lewat end_date
        $completedCount = Application::where('status_magang', Application::STATUS_BERJALAN)
            ->whereHas('internship', function($q) use ($today) {
                $q->where('end_date', '<', $today->format('Y-m-d'));
            })
            ->update(['status_magang' => Application::STATUS_SELESAI]);

        // Update ke in_progress jika sudah mencapai start_date
        $inProgressCount = Application::where('status_magang', Application::STATUS_DITERIMA)
            ->whereHas('internship', function($q) use ($today) {
                $q->where('start_date', '<=', $today->format('Y-m-d'))
                  ->where('end_date', '>=', $today->format('Y-m-d'));
            })
            ->update(['status_magang' => Application::STATUS_BERJALAN]);

        $this->info("Updated $inProgressCount magang to in_progress and $completedCount to completed.");
    }
}
