<?php

namespace App\Http\Controllers;

use App\Models\MonthlyReport;
use App\Models\MonthlyReportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'mahasiswa']);
    }
    
    /**
     * Menghapus lampiran laporan
     *
     * @param  \App\Models\MonthlyReportAttachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroyAttachment(MonthlyReportAttachment $attachment)
    {
        try {
            // Pastikan lampiran milik laporan yang dimiliki oleh user yang login
            if ($attachment->report->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus lampiran ini.'
                ], 403);
            }
            
            // Hapus file dari storage
            Storage::disk('public')->delete($attachment->file_path);
            
            // Hapus record dari database
            $attachment->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Lampiran berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus lampiran: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus lampiran.'
            ], 500);
        }
    }

    /**
     * Show the list of reports.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get reports with attachments count
        $reports = MonthlyReport::where('user_id', Auth::id())
            ->withCount('attachments')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        
        return view('mahasiswa.reports.index', compact('reports'));
    }

    /**
     * Show the form to create a new report.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $user = Auth::user();
        
        // Dapatkan data magang yang aktif
        $application = DB::table('applications')
            ->join('internships', 'applications.internship_id', '=', 'internships.id')
            ->where('applications.user_id', $user->id)
            ->where('applications.status', 'diterima')
            ->where('applications.status_magang', 'in_progress')
            ->select(
                'applications.id as application_id',
                'internships.start_date', 
                'internships.end_date'
            )
            ->first();
            
        if (!$application) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda tidak memiliki akses. Hanya untuk mahasiswa yang sedang magang.');
        }
        
        // Generate daftar bulan dalam periode magang
        $startDate = \Carbon\Carbon::parse($application->start_date)->startOfMonth();
        $endDate = \Carbon\Carbon::parse($application->end_date)->endOfMonth();
        $now = now();
        
        $months = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $months[] = [
                'value' => $current->month,
                'label' => $current->isoFormat('MMMM'),
                'year' => $current->year,
                'is_current' => $current->month == $now->month && $current->year == $now->year
            ];
            $current->addMonth();
        }
        
        // Jika tidak ada bulan yang tersedia, tambahkan bulan saat ini
        if (empty($months)) {
            $months[] = [
                'value' => $now->month,
                'label' => $now->isoFormat('MMMM'),
                'year' => $now->year,
                'is_current' => true
            ];
        }
        
        // Dapatkan tahun unik untuk dropdown tahun
        $years = array_unique(array_column($months, 'year'));
        
        return view('mahasiswa.reports.create', compact('months', 'years'));
    }

    /**
     * Store a new report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Dapatkan data magang yang aktif
        $application = DB::table('applications')
            ->join('internships', 'applications.internship_id', '=', 'internships.id')
            ->where('applications.user_id', $user->id)
            ->where('applications.status', 'diterima')
            ->where('applications.status_magang', 'in_progress')
            ->select(
                'applications.id as application_id',
                'internships.start_date', 
                'internships.end_date'
            )
            ->first();
            
        if (!$application) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda tidak memiliki akses. Hanya untuk mahasiswa yang sedang magang.');
        }

        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2000,2100',
            'tasks' => 'required|string|min:10',
            'achievements' => 'required|string|min:10',
            'challenges' => 'required|string|min:10',
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);
        
        // Validasi periode laporan sesuai masa magang
        $reportDate = \Carbon\Carbon::createFromDate(
            $request->year, 
            $request->month, 
            1
        )->endOfMonth();
        
        $startDate = \Carbon\Carbon::parse($application->start_date)->startOfMonth();
        $endDate = \Carbon\Carbon::parse($application->end_date)->endOfMonth();
        
        if ($reportDate->lt($startDate) || $reportDate->gt($endDate)) {
            return redirect()->back()
                ->with('error', 'Periode laporan harus berada dalam masa magang Anda.')
                ->withInput();
        }
        
        // Check if report for this month and year already exists
        if (MonthlyReport::where('user_id', $user->id)
                ->where('month', $request->month)
                ->where('year', $request->year)
                ->exists()) {
            return redirect()->route('mahasiswa.reports.create')
                ->with('error', 'Laporan untuk bulan dan tahun ini sudah ada.')
                ->withInput();
        }
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Debug: Pastikan application_id ada sebelum menyimpan
            if (empty($application->application_id)) {
                Log::error('Application ID is empty!', [
                    'user_id' => $user->id,
                    'application' => $application
                ]);
                throw new \Exception('Tidak dapat menemukan data aplikasi magang yang valid.');
            }
            
            // Create the report
            $report = new MonthlyReport([
                'user_id' => $user->id,
                'application_id' => $application->application_id,
                'month' => $request->month,
                'year' => $request->year,
                'tasks' => $request->tasks,
                'achievements' => $request->achievements,
                'challenges' => $request->challenges,
                'status' => MonthlyReport::STATUS_SUBMITTED,
            ]);
            
            $saved = $report->save();
            
            // Debug: Log hasil penyimpanan
            Log::info('Report saved:', [
                'saved' => $saved,
                'report_id' => $report->id,
                'application_id' => $report->application_id,
                'user_id' => $report->user_id
            ]);
            
            // Handle file uploads
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('monthly_reports/' . $report->id, 'public');
                    
                    $report->attachments()->create([
                        'file_path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }
            
            DB::commit();
            
            $message = $request->has('draft') 
                ? 'Laporan berhasil disimpan sebagai draft' 
                : 'Laporan berhasil dikirim';
                
            return redirect()->route('mahasiswa.reports.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating monthly report: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan laporan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show a report.
     *
     * @param  \App\Models\MonthlyReport  $report
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(MonthlyReport $report)
    {
        // Check if the report belongs to the authenticated user
        if ($report->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Load relationships
        $report->load(['attachments', 'reviewer']);
        
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return view('mahasiswa.reports.show', [
            'report' => $report,
            'monthNames' => $monthNames
        ]);
    }
    
    /**
     * Show the form for editing the specified report.
     *
     * @param  \App\Models\MonthlyReport  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(MonthlyReport $report)
    {
        // Pastikan laporan milik user yang login
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Pastikan status laporan bisa diedit (draft atau rejected)
        if (!in_array($report->status, ['draft', 'rejected'])) {
            return redirect()->route('mahasiswa.reports.show', $report)
                ->with('error', 'Laporan tidak dapat diedit karena status saat ini: ' . $report->status);
        }
        
        // Dapatkan data magang yang aktif
        $user = Auth::user();
        $application = DB::table('applications')
            ->join('internships', 'applications.internship_id', '=', 'internships.id')
            ->where('applications.id', $report->application_id)
            ->where('applications.user_id', $user->id)
            ->select('internships.start_date', 'internships.end_date')
            ->first();
            
        if (!$application) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data magang tidak ditemukan.');
        }
        
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return view('mahasiswa.reports.edit', compact('report', 'monthNames'));
    }
    
    /**
     * Update the specified report in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MonthlyReport  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MonthlyReport $report)
    {
        // Log request data
        \Log::info('Memperbarui laporan:', [
            'report_id' => $report->id,
            'user_id' => Auth::id()
        ]);

        // Pastikan laporan milik user yang login
        if ($report->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk memperbarui laporan ini.');
        }
        
        // Pastikan status laporan bisa diedit (draft atau rejected)
        if (!in_array($report->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Laporan tidak dapat diperbarui karena status saat ini: ' . $report->status);
        }
        
        // Validasi input dengan ketentuan yang lebih ketat
        $validated = $request->validate([
            'tasks' => [
                'required',
                'string',
                'min:10',
                'regex:/^[^\s].*[^\s].*$/' // Minimal 2 kata
            ],
            'achievements' => [
                'required',
                'string',
                'min:10',
                'regex:/^[^\s].*[^\s].*$/' // Minimal 2 kata
            ],
            'challenges' => [
                'required',
                'string',
                'min:10',
                'regex:/^[^\s].*[^\s].*$/' // Minimal 2 kata
            ],
            'attachments.*' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ], [
            'tasks.required' => 'Tugas yang dikerjakan wajib diisi',
            'tasks.min' => 'Tugas yang dikerjakan minimal 10 karakter',
            'tasks.regex' => 'Tugas harus terdiri dari minimal 2 kata',
            'achievements.required' => 'Pencapaian wajib diisi',
            'achievements.min' => 'Pencapaian minimal 10 karakter',
            'achievements.regex' => 'Pencapaian harus terdiri dari minimal 2 kata',
            'challenges.required' => 'Tantangan & solusi wajib diisi',
            'challenges.min' => 'Tantangan & solusi minimal 10 karakter',
            'challenges.regex' => 'Tantangan & solusi harus terdiri dari minimal 2 kata',
            'attachments.*.max' => 'Ukuran file lampiran maksimal 5MB',
            'attachments.*.mimes' => 'Format file lampiran harus jpg, jpeg, png, pdf, doc, atau docx',
            'delete_attachments' => 'nullable|array',
            'delete_attachments.*' => 'exists:monthly_report_attachments,id'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update field yang diperlukan
            $report->update([
                'tasks' => trim(stripslashes($request->tasks)),
                'achievements' => trim(stripslashes($request->achievements)),
                'challenges' => trim(stripslashes($request->challenges)),
                'status' => 'submitted',
                'reviewed_at' => null,
                'reviewed_by' => null,
                'feedback' => null,
                'updated_at' => now()
            ]);
            
            // Handle penghapusan lampiran yang dipilih
            if ($request->has('delete_attachments')) {
                foreach ($request->delete_attachments as $attachmentId) {
                    $attachment = MonthlyReportAttachment::find($attachmentId);
                    // Pastikan attachment milik laporan yang sedang diedit
                    if ($attachment && $attachment->monthly_report_id === $report->id) {
                        // Hapus file dari storage
                        Storage::disk('public')->delete($attachment->file_path);
                        // Hapus record dari database
                        $attachment->delete();
                    }
                }
            }
            
            // Handle upload lampiran baru
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('monthly_reports/' . $report->id, 'public');
                        
                        $report->attachments()->create([
                            'original_filename' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()
                ->route('mahasiswa.reports.show', $report)
                ->with('success', 'Laporan berhasil diperbarui dan dikirim untuk ditinjau.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal memperbarui laporan: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()
                ->with('error', 'Terjadi kesalahan saat memperbarui laporan. Silakan coba lagi.')
                ->withInput();
        }
    }
}
