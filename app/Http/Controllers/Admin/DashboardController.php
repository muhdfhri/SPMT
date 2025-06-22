<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\InternshipApplication;
use App\Models\MonthlyReport;
use App\Models\Certificate;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_applications' => Application::where('status', 'terkirim')->count(),
            'pending_reports' => MonthlyReport::whereIn('status', ['pending', 'submitted', 'menunggu review', 'draft'])->count(),
            'active_internships' => Application::where('status_magang', 'in_progress')->count(),
            'certificates_issued' => Certificate::where('status', 'published')->count(),
            'total_students' => User::where('role', 'mahasiswa')->count(),
        ];

        // Data untuk chart status magang
        $statusMagang = [
            'menunggu' => Application::where('status_magang', 'menunggu')->count(),
            'diterima' => Application::where('status_magang', 'diterima')->count(),
            'in_progress' => $stats['active_internships'],
            'completed' => Application::where('status_magang', 'completed')->count(),
            'ditolak' => Application::where('status_magang', 'ditolak')->count(),
        ];

        // Data untuk chart laporan bulanan
        $laporanBulanan = [
            'pending' => MonthlyReport::whereIn('status', ['pending', 'submitted', 'menunggu review', 'draft'])->count(),
            'approved' => MonthlyReport::where('status', 'approved')->count(),
            'rejected' => MonthlyReport::where('status', 'rejected')->count(),
        ];

        // Get recent activities
        $recentActivities = Activity::with(['causer', 'subject'])
            ->latest()
            ->take(5)
            ->get();
            
        // Get monthly applications data
        $monthlyApplications = Application::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        $monthlyData = [
            'labels' => [],
            'data' => []
        ];
        
        foreach($monthlyApplications as $app) {
            $monthName = date('M Y', mktime(0, 0, 0, $app->month, 1, $app->year));
            $monthlyData['labels'][] = $monthName;
            $monthlyData['data'][] = $app->total;
        }

        return view('admin.dashboard', compact(
            'stats', 
            'recentActivities', 
            'statusMagang', 
            'laporanBulanan',
            'monthlyData'
        ));
    }
}
