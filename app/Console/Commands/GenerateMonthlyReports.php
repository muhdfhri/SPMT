<?php

namespace App\Console\Commands;

use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateMonthlyReports extends Command
{
    protected $signature = 'reports:generate {application_id} {--force}';
    protected $description = 'Generate monthly reports for an application';

    public function handle()
    {
        $applicationId = $this->argument('application_id');
        $force = $this->option('force');

        $application = Application::with(['internship', 'monthlyReports'])->findOrFail($applicationId);

        if (!$application->internship) {
            $this->error('Application does not have an associated internship.');
            return 1;
        }

        $existingMonths = $application->monthlyReports->pluck('month')->toArray();
        $startDate = Carbon::parse($application->internship->start_date);
        $endDate = Carbon::parse($application->internship->end_date);
        
        $currentDate = $startDate->copy();
        $reportsCreated = 0;

        while ($currentDate->lte($endDate)) {
            $monthYear = $currentDate->format('Y-m');
            
            if (!in_array($monthYear, $existingMonths) || $force) {
                // Create or update monthly report
                $month = $currentDate->format('n'); // Angka bulan (1-12)
                $year = $currentDate->format('Y'); // Tahun (4 digit)
                
                // Data untuk laporan bulanan sesuai struktur tabel
                $reportData = [
                    'month' => $month,
                    'year' => $year,
                    'application_id' => $application->id,
                    'user_id' => $application->user_id,
                    'status' => 'approved',
                    'tasks' => json_encode(['Tugas bulanan telah diselesaikan']),
                    'achievements' => json_encode(['Pencapaian bulanan telah tercapai']),
                    'challenges' => json_encode(['Tidak ada kendala yang signifikan']),
                    'reviewed_by' => 1, // ID admin yang menyetujui
                    'reviewed_at' => now(),
                    'feedback' => 'Laporan disetujui secara otomatis',
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                // Update atau buat laporan bulanan
                DB::table('monthly_reports')->updateOrInsert(
                    [
                        'month' => $month,
                        'year' => $year,
                        'application_id' => $application->id
                    ],
                    $reportData
                );
                $reportsCreated++;
            }

            $currentDate->addMonth();
        }

        $this->info("Successfully created/updated {$reportsCreated} monthly reports for application ID: {$applicationId}");
        return 0;
    }
}
