<?php

namespace App\Listeners;

use App\Events\ApplicationStatusUpdated;
use App\Models\Application;
use App\Models\Internship;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateInternshipQuota
{
    /**
     * Handle the event when application status is updated.
     *
     * @param  \App\Events\ApplicationStatusUpdated  $event
     * @return void
     */
    public function handle(ApplicationStatusUpdated $event)
    {
        // Gunakan lock untuk mencegah race condition
        $lock = Cache::lock('internship_quota_update_' . $event->application->internship_id, 10);
        
        try {
            if (!$lock->get()) {
                throw new \Exception('Gagal mendapatkan lock untuk update kuota');
            }
            
            Log::info('\n===== [UpdateInternshipQuota] DIPANGGIL =====', [
                'time' => now()->toDateTimeString(),
                'application_id' => $event->application->id,
                'internship_id' => $event->application->internship_id,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus
            ]);

            // Mulai transaksi database
            DB::beginTransaction();
            
            try {
                // Load application with internship relationship
                $application = $event->application->load('internship');
                
                if (!$application->internship) {
                    throw new \Exception('Internship not found for application: ' . $application->id);
                }

                $internship = $application->internship;
                
                // Dapatkan nilai kuota langsung dari database untuk memastikan data terbaru
                $oldQuota = DB::table('internships')
                    ->where('id', $internship->id)
                    ->lockForUpdate() // Lock row untuk mencegah race condition
                    ->value('quota');
                
                Log::info('Data Awal:', [
                    'internship_id' => $internship->id,
                    'internship_title' => $internship->title,
                    'quota_awal' => $oldQuota,
                    'status_lama' => $event->oldStatus,
                    'status_baru' => $event->newStatus,
                    'waktu' => now()->toDateTimeString()
                ]);

                $action = null;
                $newQuota = $oldQuota;
                
                // Hanya proses jika ada perubahan status yang relevan
                if ($event->newStatus === 'diterima' && $event->oldStatus !== 'diterima') {
                    if ($oldQuota <= 0) {
                        throw new \Exception('Kuota sudah habis');
                    }
                    
                    // Update quota langsung di database
                    $newQuota = $oldQuota - 1;
                    $updated = DB::table('internships')
                        ->where('id', $internship->id)
                        ->where('quota', $oldQuota) // Pastikan kuota belum berubah
                        ->update(['quota' => $newQuota]);
                    
                    if ($updated === 0) {
                        throw new \Exception('Gagal update kuota - data sudah berubah');
                    }
                    
                    $action = 'dikurangi';
                    
                    Log::info('Mengurangi kuota:', [
                        'quota_sebelum' => $oldQuota,
                        'quota_sesudah' => $newQuota,
                        'selisih' => $oldQuota - $newQuota
                    ]);
                    
                } elseif ($event->oldStatus === 'diterima' && $event->newStatus !== 'diterima') {
                    // Update quota langsung di database
                    $newQuota = $oldQuota + 1;
                    $updated = DB::table('internships')
                        ->where('id', $internship->id)
                        ->where('quota', $oldQuota) // Pastikan kuota belum berubah
                        ->update(['quota' => $newQuota]);
                    
                    if ($updated === 0) {
                        throw new \Exception('Gagal update kuota - data sudah berubah');
                    }
                    
                    $action = 'ditambah';
                    
                    Log::info('Menambah kuota:', [
                        'quota_sebelum' => $oldQuota,
                        'quota_sesudah' => $newQuota,
                        'selisih' => $newQuota - $oldQuota
                    ]);
                } else {
                    Log::info('Tidak ada perubahan kuota yang diperlukan', [
                        'alasan' => 'Status tidak berubah dari/ke "diterima"',
                        'status_lama' => $event->oldStatus,
                        'status_baru' => $event->newStatus
                    ]);
                }

                // Commit transaksi
                DB::commit();
                
                // Verifikasi perubahan di database
                $verifiedQuota = DB::table('internships')
                    ->where('id', $internship->id)
                    ->value('quota');
                
                Log::info('✅ Berhasil update kuota', [
                    'internship_id' => $internship->id,
                    'quota_awal' => $oldQuota,
                    'quota_akhir' => $newQuota,
                    'quota_terverifikasi' => $verifiedQuota,
                    'action' => $action ?? 'tidak ada perubahan',
                    'waktu' => now()->toDateTimeString()
                ]);
                
                Log::info('===== [UpdateInternshipQuota] SELESAI =====\n');

            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi error
                if (DB::transactionLevel() > 0) {
                    DB::rollBack();
                }
                
                Log::error('❌ Gagal update kuota: ' . $e->getMessage(), [
                    'application_id' => $event->application->id,
                    'internship_id' => $event->application->internship_id,
                    'error' => $e->getTraceAsString(),
                    'waktu' => now()->toDateTimeString()
                ]);
                
                // Tetap lempar exception agar Laravel mencatatnya
                throw $e;
            }
        } finally {
            // Pastikan lock selalu dilepas
            $lock->release();
        }
    
    }
}
