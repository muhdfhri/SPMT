<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Helpers\DivisionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\Activity as ActivityLog;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    
    /**
     * Menyelesaikan magang
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeInternship($id)
    {
        try {
            // Log request
            \Log::info('Starting completeInternship', [
                'application_id' => $id,
                'user_id' => auth()->id(),
                'time' => now()->toDateTimeString()
            ]);
            
            $application = Application::with(['monthlyReports', 'internship'])->findOrFail($id);
            
            // Log application data
            \Log::info('Application data:', [
                'status' => $application->status,
                'status_magang' => $application->status_magang,
                'internship' => $application->internship ? [
                    'id' => $application->internship->id,
                    'start_date' => $application->internship->start_date,
                    'end_date' => $application->internship->end_date
                ] : null,
                'monthly_reports_count' => $application->monthlyReports->count(),
                'monthly_reports_statuses' => $application->monthlyReports->pluck('status', 'id')
            ]);
            
            // Pastikan status magang sedang in_progress
            if ($application->status_magang !== 'in_progress') {
                $error = 'Status magang tidak valid. Current status: ' . $application->status_magang;
                \Log::error($error);
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 400);
            }
            
            // Validasi semua laporan sudah disetujui
            if (!$application->hasCompletedAllReports()) {
                $error = 'Laporan belum lengkap atau belum disetujui semua';
                \Log::error($error, [
                    'expected_months' => $application->internship ? 
                        (\Carbon\Carbon::parse($application->internship->start_date)->diffInMonths(
                            \Carbon\Carbon::parse($application->internship->end_date)
                        ) + 1) : null,
                    'approved_reports' => $application->monthlyReports->where('status', 'approved')->count(),
                    'all_reports' => $application->monthlyReports->pluck('status', 'id')
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => $error
                ], 400);
            }
            
            // Update status magang
            $application->update([
                'status_magang' => 'completed',
                'completed_at' => now()
            ]);
            
            // Log activity
            ActivityLog::causedBy(Auth::user())
                ->performedOn($application)
                ->withProperties(['status_magang' => 'completed'])
                ->log('Menyelesaikan magang');
            
            \Log::info('Successfully completed internship', [
                'application_id' => $id,
                'new_status' => 'completed'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Status magang berhasil diupdate menjadi Selesai. Sekarang Anda bisa mengeluarkan sertifikat.',
                'new_status' => 'Selesai'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in completeInternship: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'application_id' => $id ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mulai magang untuk aplikasi yang diterima
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function startInternship($id)
    {
        try {
            $application = Application::findOrFail($id);
            
            // Pastikan status sudah diterima
            if ($application->status !== 'diterima') {
                return redirect()->back()
                    ->with('error', 'Hanya bisa memulai magang untuk lamaran yang sudah diterima');
            }
            
            // Pastikan status magang belum in_progress atau completed
            if (in_array($application->status_magang, ['in_progress', 'completed'])) {
                return redirect()->back()
                    ->with('error', 'Status magang sudah ' . ($application->status_magang === 'in_progress' ? 'berjalan' : 'selesai'));
            }
            
            // Update status magang
            $application->update([
                'status_magang' => 'in_progress',
                'started_at' => now()
            ]);
            
            // Log activity
            ActivityLog::create([
                'log_name' => 'internship',
                'description' => 'Memulai magang untuk ' . $application->user->name,
                'subject_type' => get_class($application),
                'subject_id' => $application->id,
                'causer_type' => get_class(Auth::user()),
                'causer_id' => Auth::id(),
            ]);
            
            return redirect()->back()
                ->with('success', 'Status magang berhasil diubah menjadi Berjalan');
                
        } catch (\Exception $e) {
            \Log::error('Error starting internship: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memulai magang');
        }
    }
    
    public function index(Request $request)
    {
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        $query = Application::with(['user', 'internship', 'approver'])
            ->join('internships', 'applications.internship_id', '=', 'internships.id')
            ->leftJoin('users', 'users.id', '=', 'applications.user_id')
            ->select('applications.*');
            
        // Apply sorting
        $sortableFields = [
            'name' => 'users.name',
            'email' => 'users.email',
            'title' => 'internships.title',
            'division' => 'internships.division',
            'status' => 'applications.status',
            'created_at' => 'applications.created_at'
        ];
        
        if (array_key_exists($sortField, $sortableFields)) {
            $query->orderBy($sortableFields[$sortField], $sortDirection);
        } else {
            $query->latest('applications.created_at');
        }
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $validStatuses = ['terkirim', 'diproses', 'diterima', 'ditolak'];
            if (in_array($request->status, $validStatuses)) {
                $query->where('applications.status', $request->status);
            }
        }
        
        // Search by user name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('users.name', 'like', "%{$search}%");
        }
        
        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('applications.created_at', '=', date('m', strtotime($request->month)))
                  ->whereYear('applications.created_at', '=', date('Y', strtotime($request->month)));
        }
        
        // Filter by division
        if ($request->filled('division') && $request->division !== 'all') {
            $query->where('internships.division', $request->division);
        }
        
        // Get all available divisions from helper
        $divisions = DivisionHelper::getAllDivisions();
        
        // Get per_page from request or use default 10
        $perPage = $request->input('per_page', 10);
        
        // If 'all' is selected, set a high number to show all records
        $perPage = $perPage === 'all' ? PHP_INT_MAX : $perPage;
        
        $applications = $query->paginate((int)$perPage)->withQueryString();
        
        return view('admin.applications.index', compact('applications', 'divisions'));
    }

    /**
     * Display the specified resource.
     */
    /**
     * Process the specified application.
     */
    public function process(Request $request, Application $application)
    {
        try {
            \Log::info('Process method called', [
                'application_id' => $application->id,
                'current_status' => $application->status,
                'user_id' => auth()->id()
            ]);

            // Validasi status saat ini
            if ($application->status !== 'terkirim') {
                $message = 'Hanya lamaran dengan status Terkirim yang bisa diproses. Status saat ini: ' . $application->status;
                \Log::warning($message);
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return back()->with('error', $message);
            }
            
            // Cek apakah user sudah memiliki magang aktif
            $activeApplication = Application::where('user_id', $application->user_id)
                ->where('id', '!=', $application->id)
                ->where('status', 'diterima')
                ->whereIn('status_magang', ['menunggu', 'diterima', 'in_progress'])
                ->first();

            if ($activeApplication) {
                $errorMessage = 'Mahasiswa sudah memiliki magang aktif di ' . 
                              ($activeApplication->internship ? $activeApplication->internship->title : '') . 
                              '. Tolak atau selesaikan magang tersebut terlebih dahulu.';
                \Log::warning($errorMessage, ['user_id' => $application->user_id]);
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                
                return back()->with('error', $errorMessage);
            }
            
            // Validasi tambahan menggunakan method hasActiveInternship
            if ($application->user->hasActiveInternship()) {
                $message = 'Mahasiswa ini sudah memiliki magang aktif';
                \Log::warning($message, ['user_id' => $application->user_id]);
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'has_active_internship' => true
                    ], 422);
                }
                
                return back()->with('error', $message);
            }

            DB::beginTransaction();
            
            // Update status to diproses (in review)
            $application->status = 'diproses';
            $application->processed_by = auth()->id();
            $application->processed_at = now();
            
            $application->save();
            
            // Kirim notifikasi ke user bahwa lamaran sedang diproses
            $application->user->sendApplicationNotification('diproses', $application);
            \Log::info('Updating application status to diproses', [
                'application_id' => $application->id,
                'new_status' => 'diproses'
            ]);
            
            
            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($application)
                ->withProperties(['status' => 'diproses'])
                ->log('Memproses lamaran magang');
                
            DB::commit();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lamaran berhasil diproses',
                    'data' => $application->fresh()
                ]);
            }
            
            return back()->with('success', 'Lamaran berhasil diproses');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error processing application: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses lamaran: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal memproses lamaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        $application->load([
            'user.studentProfile',
            'user.studentProfile.educations',
            'user.studentProfile.familyMembers',
            'user.studentProfile.documents',
            'internship',
            'approver',
            'rejector'
        ]);
        
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Approve the specified application.
     */
    public function approve(Request $request, Application $application)
    {
        try {
            // Validasi status saat ini
            if ($application->status !== 'diproses') {
                $message = 'Hanya lamaran dengan status Diproses yang bisa diterima. Status saat ini: ' . $application->status;
                \Log::warning($message);
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return back()->with('error', $message);
            }
            
            DB::beginTransaction();
            
            // Cek apakah user sudah punya magang aktif
            if ($application->user->hasActiveInternship()) {
                $activeApplication = $application->user->activeInternship;
                $message = 'Mahasiswa ini sudah memiliki magang aktif';
                \Log::warning($message, [
                    'user_id' => $application->user_id,
                    'active_application_id' => $activeApplication ? $activeApplication->id : null
                ]);
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'has_active_internship' => true,
                        'active_application' => $activeApplication
                    ], 422);
                }
                
                return back()->with('error', $message);
            }
            
            // Validasi tambahan menggunakan method hasActiveInternship
            if ($application->user->hasActiveInternship()) {
                DB::rollBack();
                $errorMessage = 'Mahasiswa sudah memiliki magang aktif.';
                
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                return back()->with('error', $errorMessage);
            }

            // Update status to diterima (accepted)
            $application->status = 'diterima';
            $application->status_magang = 'diterima';
            $application->approved_by = auth()->id();
            $application->approved_at = now();
            $application->rejected_by = null;
            $application->rejected_at = null;
            $application->rejection_reason = null;
            
            // Save notes if provided
            if ($request->has('notes')) {
                $application->notes = $request->notes;
            }
            
            $application->save();
            
            // Update related internship status if needed
            if ($application->internship) {
                $application->internship->is_active = true;
                $application->internship->save();
            }
            
            // Kirim notifikasi ke user bahwa lamaran diterima
            $message = "Selamat! Lamaran Anda untuk {$application->internship->title} telah DITERIMA. " .
                     "Harap periksa email Anda untuk informasi lebih lanjut.";
            $application->user->sendApplicationNotification('diterima', $application, $message);
            
            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($application)
                ->withProperties(['status' => 'diterima'])
                ->log('Melakukan persetujuan aplikasi magang');
                
            DB::commit();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lamaran berhasil disetujui',
                    'data' => $application->fresh()
                ]);
            }
            
            return redirect()
                ->route('admin.applications.show', $application)
                ->with('success', 'Lamaran berhasil disetujui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error approving application: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyetujui lamaran: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menyetujui lamaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject the specified application.
     */
    public function reject(Request $request, Application $application)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);
        
        try {
            // Validasi status saat ini
            if ($application->status !== 'diproses') {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hanya lamaran dengan status Diproses yang bisa ditolak'
                    ], 422);
                }
                return back()->with('error', 'Hanya lamaran dengan status Diproses yang bisa ditolak');
            }

            DB::beginTransaction();
            
            // Update status to ditolak (rejected)
            $application->status = 'ditolak';
            $application->status_magang = 'ditolak'; // Update status_magang to 'ditolak' as well
            $application->rejected_by = auth()->id();
            $application->rejected_at = now();
            $application->rejection_reason = $request->rejection_reason;
            $application->approved_by = null;
            $application->approved_at = null;
            
            $application->save();
            
            // Update related internship status if needed
            if ($application->internship) {
                $application->internship->is_active = false;
                $application->internship->save();
            }
            
            // Kirim notifikasi ke user bahwa lamaran ditolak
            $rejectionReason = $request->rejection_reason ? "Alasan: {$request->rejection_reason}" : "";
            $message = "Maaf, lamaran Anda untuk {$application->internship->title} tidak dapat diproses. {$rejectionReason}";
            $application->user->sendApplicationNotification('ditolak', $application, $message);
            
            DB::commit();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lamaran berhasil ditolak',
                    'data' => $application->fresh()
                ]);
            }
            
            return redirect()
                ->route('admin.applications.show', $application)
                ->with('success', 'Lamaran berhasil ditolak');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error rejecting application: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak lamaran: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menolak lamaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Update status magang
     */
    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak,in_progress,completed'
        ]);

        $oldStatus = $application->status_magang;
        $newStatus = $request->status;

        // Pastikan ada perubahan status
        if ($oldStatus !== $newStatus) {
            // Gunakan update untuk memaksa event updated
            $application->update([
                'status_magang' => $newStatus,
                'processed_by' => Auth::id(),
                'processed_at' => now()
            ]);
            
            // Log activity
            ActivityLog::log(
                'Mengubah status magang dari ' . $oldStatus . ' menjadi ' . $newStatus,
                $application->id,
                'application',
                'update'
            );
            
            // Log untuk debugging
            \Log::info('Status diupdate melalui controller', [
                'application_id' => $application->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'internship_id' => $application->internship_id
            ]);
            
            // Paksa dispatch event secara manual
            event(new \App\Events\ApplicationStatusUpdated(
                $application,
                $oldStatus,
                $newStatus
            ));
        } else {
            \Log::info('Tidak ada perubahan status', [
                'application_id' => $application->id,
                'status' => $newStatus
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status magang berhasil diperbarui',
            'status' => $newStatus,
            'status_label' => $this->getStatusLabel($newStatus)
        ]);
    }
    
    /**
     * Get label for status
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak',
            'in_progress' => 'Sedang Berjalan',
            'completed' => 'Selesai'
        ];
        
        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
