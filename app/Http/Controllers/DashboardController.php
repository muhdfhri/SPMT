<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Models\MonthlyReport;
use App\Models\StudentProfile;
use App\Models\Certificate;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', \App\Http\Middleware\EnsureMahasiswaRole::class]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $profile = $user->studentProfile;
            
            // Get user's applications
            $applications = Application::where('user_id', $user->id)
                ->with('internship')
                ->get();
                
            // Get user's reports
            $reports = MonthlyReport::where('user_id', $user->id)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
                
            // Get user's certificates
            $certificates = Certificate::where('user_id', $user->id)
                ->with(['internship', 'application'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Handle any session messages
            $messages = [];
            
            if (session('error')) {
                $error = session('error');
                $messages[] = [
                    'type' => 'error',
                    'title' => is_array($error) ? ($error['title'] ?? 'Error') : 'Error',
                    'message' => is_array($error) ? ($error['message'] ?? $error) : $error
                ];
            }
            
            if (session('success')) {
                $success = session('success');
                $messages[] = [
                    'type' => 'success',
                    'title' => is_array($success) ? ($success['title'] ?? 'Sukses') : 'Sukses',
                    'message' => is_array($success) ? ($success['message'] ?? $success) : $success
                ];
            }
            
            if (session('warning')) {
                $warning = session('warning');
                $messages[] = [
                    'type' => 'warning',
                    'title' => is_array($warning) ? ($warning['title'] ?? 'Peringatan') : 'Peringatan',
                    'message' => is_array($warning) ? ($warning['message'] ?? $warning) : $warning
                ];
            }
            
            // Add messages to the view
            view()->share('alertMessages', $messages);
            
            return view('mahasiswa.dashboard', compact(
                'user', 
                'profile', 
                'applications', 
                'reports',
                'certificates'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in DashboardController: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Return with a safe error message
            session()->flash('error', [
                'title' => 'Terjadi Kesalahan',
                'message' => 'Gagal memuat dashboard. Silakan coba lagi nanti.'
            ]);
            
            return view('mahasiswa.dashboard', [
                'user' => Auth::user(),
                'profile' => null,
                'applications' => collect(),
                'reports' => collect(),
                'certificates' => collect()
            ]);
        }
    }
}