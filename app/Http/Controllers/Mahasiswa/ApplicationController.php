<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    /**
     * Menyimpan pendaftaran magang baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $internshipId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $internshipId)
    {
        // Cek apakah user sudah memiliki magang aktif
        if (auth()->user()->hasActiveInternship()) {
            return redirect()->back()
                ->with('error', 'Anda sudah memiliki magang aktif. Tidak bisa mengajukan lamaran baru.');
        }
        
        // Validasi apakah lowongan tersedia
        $internship = Internship::findOrFail($internshipId);
        
        // Cek apakah lowongan masih dibuka
        if (!$internship->isOpenForApplication()) {
            return redirect()->back()
                ->with('error', 'Pendaftaran untuk lowongan ini sudah ditutup.');
        }
        
        // Cek apakah user sudah mendaftar sebelumnya
        $existingApplication = Application::where('user_id', Auth::id())
            ->where('internship_id', $internshipId)
            ->exists();
            
        if ($existingApplication) {
            return redirect()->back()
                ->with('error', 'Anda sudah mendaftar untuk lowongan ini.');
        }
        
        // Mulai transaksi database
        DB::beginTransaction();
        
        try {
            // Buat aplikasi baru
            $application = new Application();
            $application->user_id = Auth::id();
            $application->internship_id = $internshipId;
            $application->status = 'terkirim';
            $application->save();
            
            // Kirim notifikasi ke user
            Auth::user()->sendApplicationNotification('terkirim', $application);
            
            DB::commit();
            
            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Pendaftaran berhasil dikirim. Silakan tunggu konfirmasi lebih lanjut.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat mendaftar lowongan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses pendaftaran. Silakan coba lagi.');
        }
    }
    
    /**
     * Menampilkan daftar lamaran yang sudah diajukan
     *
     * @return \Illuminate\View\View
     */
    public function myApplications()
    {
        $applications = Application::with('internship')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('mahasiswa.applications.index', compact('applications'));
    }
}