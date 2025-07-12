<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan kita memanggil query builder dengan benar
        $query = $user->certificates()
            ->with(['internship', 'user.studentProfile'])
            ->latest();
            
        // Pastikan kita mendapatkan instance paginator
        $certificates = $query->paginate(10);
        
        // Debug: Uncomment baris berikut untuk memeriksa tipe data
        // dd(get_class($certificates), $certificates);
            
        return view('mahasiswa.certificates.index', compact('certificates'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        // Verify ownership
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $certificate->load(['internship', 'user.studentProfile']);
        return view('mahasiswa.certificates.show', compact('certificate'));
    }

    /**
     * Download the specified certificate.
     */
    public function download(Certificate $certificate)
    {
        // Verify ownership
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengunduh sertifikat ini.');
        }
        
        // Cek status sertifikat
        if ($certificate->status === 'revoked') {
            return response()->json([
                'status' => 'revoked',
                'message' => 'Sertifikat ini telah dicabut dan tidak dapat diunduh.',
                'revoked_at' => $certificate->revoked_at ? $certificate->revoked_at->format('d M Y') : null,
                'revoked_reason' => $certificate->revoked_reason ?? 'Alasan tidak tersedia',
                'revoked_by' => $certificate->revokedBy ? $certificate->revokedBy->name : 'Administrator'
            ], 403);
        }

        // Pastikan certificate_path tidak kosong
        if (empty($certificate->certificate_path)) {
            abort(404, 'Path sertifikat tidak valid.');
        }

        // Buat path lengkap ke file
        $filePath = storage_path('app/public/' . ltrim($certificate->certificate_path, '/'));
        
        // Log path yang dicari
        \Log::info('Mencari sertifikat di:', [
            'database_path' => $certificate->certificate_path,
            'full_path' => $filePath,
            'exists' => file_exists($filePath) ? 'Ya' : 'Tidak'
        ]);
        
        // Periksa apakah file ada
        if (!file_exists($filePath)) {
            // Coba cari file di lokasi alternatif
            $alternatePath = storage_path('app/public/certificates/' . basename($certificate->certificate_path));
            \Log::info('Mencari di lokasi alternatif:', ['path' => $alternatePath]);
            
            if (file_exists($alternatePath)) {
                $filePath = $alternatePath;
                \Log::info('File ditemukan di lokasi alternatif');
            } else {
                // Cek apakah direktori ada
                $directory = dirname($filePath);
                $filesInDir = [];
                if (is_dir($directory)) {
                    $filesInDir = scandir($directory);
                }
                
                \Log::error('File sertifikat tidak ditemukan', [
                    'expected_path' => $filePath,
                    'alternate_path' => $alternatePath,
                    'directory_exists' => is_dir($directory) ? 'Ya' : 'Tidak',
                    'files_in_directory' => $filesInDir
                ]);
                
                abort(404, 'File sertifikat tidak ditemukan. Silakan hubungi administrator.');
            }
        }
        
        // Buat nama file yang aman untuk diunduh
        $userName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $certificate->user->name);
        $certNumber = basename($certificate->certificate_number); // Hapus path dari nomor sertifikat
        $fileName = 'Sertifikat_Magang_' . $userName . '_' . $certNumber . '.pdf';
        
        // Hapus karakter ilegal dari nama file
        $fileName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $fileName);
        
        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
