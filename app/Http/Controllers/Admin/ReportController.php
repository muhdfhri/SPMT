<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search');
    $perPage = $request->input('per_page', 10) === 'all' ? PHP_INT_MAX : $request->input('per_page', 10);
    
    $sortField = $request->input('sort_field', 'name');
    $sortDirection = $request->input('sort_direction', 'asc');
    
    $query = User::whereHas('monthlyReports')
        ->with([
            'studentProfile',
            'applications' => function($query) {
                $query->where('status', 'diterima')
                      ->whereIn('status_magang', ['in_progress', 'selesai'])
                      ->whereNotNull('internship_id')
                      ->latest()
                      ->with(['internship', 'monthlyReports']);
            },
            'monthlyReports' => function($query) {
                $query->orderBy('created_at', 'desc')
                      ->with(['application.internship']);
            }
        ])
        ->when($sortField === 'name', function($q) use ($sortDirection) {
            return $q->orderBy('name', $sortDirection);
        }, function($q) use ($sortField, $sortDirection) {
            // Default sorting if no valid sort field
            return $q->orderBy('name', 'asc');
        })
        ->withCount([
            'monthlyReports as total_reports_count',
            'monthlyReports as pending_reviews_count' => function($query) {
                $query->whereIn('status', ['submitted', 'Menunggu Review', 'Pending', 'pending']);
            },
            'monthlyReports as approved_reports_count' => function($query) {
                $query->where('status', 'approved');
            }
        ]);
            
        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $searchTerm = "%{$search}%";
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhereHas('studentProfile', function($q) use ($searchTerm) {
                      $q->where('full_name', 'like', $searchTerm)
                        ->orWhere('nik', 'like', $searchTerm);
                  });
            });
        }
        
        $users = $query->paginate($perPage)->withQueryString();
            
        return view('admin.reports.index', compact('users'));
    }


    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not implemented - reports are created by students
        abort(403, 'Unauthorized action.');
    }

    /**
     * Approve a monthly report.
     */
    public function approve(MonthlyReport $report)
    {
        DB::transaction(function () use ($report) {
            // Load the application with its relationships
            $application = $report->application()->with(['internship', 'monthlyReports'])->first();
            
            if (!$application) {
                throw new \Exception('Application not found for this report');
            }

            // Approve the report
            $report->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now()
            ]);

            // Log before checking reports
            \Log::info('Approving report', [
                'report_id' => $report->id,
                'application_id' => $application->id,
                'current_status' => $application->status_magang,
                'has_all_reports' => $application->hasCompletedAllReports()
            ]);

            // Log that all reports are approved
            if ($application->hasCompletedAllReports()) {
                \Log::info('Semua laporan telah disetujui, menunggu konfirmasi selesai dari admin', [
                    'application_id' => $application->id,
                    'status_magang' => $application->status_magang
                ]);
            }
        });

        return redirect()->back()
            ->with('success', 'Laporan berhasil disetujui');
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        // Temukan report berdasarkan ID
        $report = MonthlyReport::with(['user.studentProfile', 'application.internship'])
            ->findOrFail($id);
        try {
            // Pastikan data yang diperlukan ada
            if (!$report->user || !$report->application) {
                return redirect()->route('admin.reports.index')
                    ->with('error', 'Data magang tidak ditemukan untuk laporan ini.');
            }

            // Load necessary relationships
            $report->load(['attachments', 'user.studentProfile']);
            
            // Check if application exists
            if (!$report->application_id) {
                // If no application_id, try to find the most recent application
                $application = \App\Models\Application::where('user_id', $report->user_id)
                    ->where('status', 'diterima')
                    ->whereIn('status_magang', ['in_progress', 'selesai'])
                    ->latest()
                    ->first();
                    
                if ($application) {
                    // Update the report with the found application_id
                    $report->update(['application_id' => $application->id]);
                    $report->refresh();
                } else {
                    return redirect()->back()
                        ->with('error', 'Tidak dapat menemukan data magang yang valid untuk laporan ini.');
                }
            }
            
            // Reload with all necessary relationships
            $report->load([
                'application.internship',
                'attachments',
                'user.studentProfile'
            ]);
            
            if (!$report->application) {
                return redirect()->back()
                    ->with('error', 'Data magang tidak ditemukan untuk laporan ini.');
            }
            
            // Get all reports for the same user
            $allReports = MonthlyReport::where('user_id', $report->user_id)
                ->with('attachments')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
            
            // Gunakan tanggal dari internship jika tersedia, jika tidak gunakan dari application
            $startDate = $report->application->internship->start_date ?? $report->application->start_date;
            $endDate = $report->application->internship->end_date ?? $report->application->end_date;

            $startDate = \Carbon\Carbon::parse($startDate);
            $endDate = \Carbon\Carbon::parse($endDate);
            
            // Generate array bulan untuk tab navigation
            $months = [];
            $current = $startDate->copy()->startOfMonth();
            $end = $endDate->copy()->endOfMonth();
            
            while ($current <= $end) {
                $monthKey = $current->format('Y-m');
                $months[$monthKey] = [
                    'year' => $current->year,
                    'month' => $current->month,
                    'label' => $current->translatedFormat('F Y')
                ];
                $current->addMonth();
            }
            
            // Ambil bulan dan tahun dari request atau gunakan dari laporan saat ini
            $month = $request->input('month', $report->month);
            $year = $request->input('year', $report->year);
            
            // Cari laporan untuk bulan dan tahun yang diminta
            $currentReport = $allReports->first(function($r) use ($month, $year) {
                return $r->month == $month && $r->year == $year;
            });
            
            // Jika tidak ada laporan untuk bulan dan tahun yang diminta, gunakan laporan yang ada
            // Set current month and year for view
            $currentMonth = $month;
            $currentYear = $year;
            
            // Check if report exists for the current month and year
            $hasReport = $currentReport !== null;
            
            // If no report found for the requested month, create a temporary report object
            // to avoid errors in the view
            if (!$hasReport) {
                $currentReport = new MonthlyReport([
                    'month' => $month,
                    'year' => $year,
                    'tasks' => [],
                    'achievements' => [],
                    'challenges' => [],
                    'status' => 'belum_terisi',
                    'user_id' => $report->user_id,
                    'application_id' => $report->application_id
                ]);
            }
            
            // Load relasi untuk laporan yang akan ditampilkan
            $currentReport->load(['attachments']);
            
            // Nama-nama bulan dalam bahasa Indonesia
            $monthNames = [
                1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            
            return view('admin.reports.show', [
                'report' => $currentReport,
                'allReports' => $allReports,
                'months' => $months,
                'currentMonth' => (int)$month,
                'currentYear' => (int)$year,
                'hasReport' => $currentReport->exists,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'monthNames' => $monthNames
            ]);
        
    } catch (\Exception $e) {
        \Log::error('Error in ReportController@show: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat memuat laporan: ' . $e->getMessage());
    }
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MonthlyReport $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(MonthlyReport::$statuses)),
            'feedback' => 'nullable|string|max:1000',
        ]);
        
        try {
            DB::beginTransaction();
            
            $oldStatus = $report->status;
            $newStatus = $validated['status'];
            
            $report->status = $newStatus;
            $report->feedback = $validated['feedback'];
            $report->reviewed_by = Auth::id();
            $report->reviewed_at = now();
            $report->save();
            
            // Log activity
            activity()
                ->performedOn($report)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'feedback' => $validated['feedback'],
                ])
                ->log('updated report status');
            
            // Send notification to student if status changed
            if ($oldStatus !== $newStatus) {
                $user = $report->user;
                $message = "Laporan bulanan Anda untuk " . \Carbon\Carbon::create($report->year, $report->month, 1)->translatedFormat('F Y') . " telah diperbarui menjadi " . 
                    ($newStatus === 'approved' ? 'Disetujui' : ($newStatus === 'rejected' ? 'Ditolak' : 'Menunggu Review')) . ".";
                
                $user->notify(new \App\Notifications\ReportStatusUpdated($report, $message));
            }
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status laporan berhasil diperbarui.',
                    'data' => [
                        'status' => $newStatus,
                        'status_label' => $newStatus === 'approved' ? 'Disetujui' : ($newStatus === 'rejected' ? 'Ditolak' : 'Menunggu Review'),
                        'feedback' => $validated['feedback'],
                        'reviewed_at' => $report->reviewed_at->format('d M Y H:i'),
                        'reviewer_name' => Auth::user()->name,
                    ]
                ]);
            }
            
            return redirect()->route('admin.reports.show', $report)
                ->with('success', 'Status laporan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating report status: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui status laporan.'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui status laporan.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MonthlyReport $report)
    {
        // Not implemented - reports should not be deleted
        abort(403, 'Unauthorized action.');
    }
}
