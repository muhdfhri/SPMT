<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;
use PDF;
use Carbon\Carbon;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $sortField = $request->input('sort_field', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        // Get all applications with completed reports that are eligible for certificates
        $eligibleApplications = \App\Models\Application::with(['user.studentProfile', 'internship', 'certificate'])
            ->whereIn('status', ['diterima', 'selesai'])
            ->whereHas('monthlyReports', function($q) {
                $q->where('status', 'approved');
            }, '>=', \DB::raw('(SELECT TIMESTAMPDIFF(MONTH, internships.start_date, internships.end_date) + 1 FROM internships WHERE internships.id = applications.internship_id)'))
            ->join('users', 'applications.user_id', '=', 'users.id')
            ->select('applications.*')
            ->orderBy('users.name', $sortDirection)
            ->get()
            ->filter(function($application) {
                return $application->hasCompletedAllReports();
            });
        
        // Build certificate query with filters
        $query = Certificate::with(['user.studentProfile', 'application.internship'])
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    // Search in user name
                    $q->whereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                    
                    // Search in student profile (full name and NIK)
                    $q->orWhereHas('user.studentProfile', function($q) use ($search) {
                        $q->where('full_name', 'like', "%{$search}%")
                          ->orWhere('nik', 'like', "%{$search}%");
                    });
                    
                    // Search in certificate number
                    $q->orWhere('certificate_number', 'like', "%{$search}%");
                });
            })
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            });
            
        // Filter by date range if provided
        if ($startDate) {
            $query->whereDate('issue_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('issue_date', '<=', $endDate);
        }
        
        // Apply sorting
        if ($sortField === 'name') {
            $query->join('users', 'certificates.user_id', '=', 'users.id')
                 ->orderBy('users.name', $sortDirection);
        } else {
            $query->latest();
        }
        
        $certificates = $query->paginate(10)->withQueryString();
        
        return view('admin.certificates.index', compact(
            'certificates', 
            'eligibleApplications',
            'sortField',
            'sortDirection'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Not implemented directly - certificates are generated via the generate method
        abort(403, 'Unauthorized action.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['user.studentProfile', 'application.internship']);
        return view('admin.certificates.show', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        // Not implemented - certificates should not be updated directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Certificate $certificate)
    {
        // Not implemented - certificates should not be deleted
        abort(403, 'Unauthorized action.');
    }
    
    /**
     * Generate a certificate for a user
     */
    public function generate($applicationId)
    {
        $application = \App\Models\Application::with(['user', 'internship', 'monthlyReports'])
            ->findOrFail($applicationId);
            
        // Validasi aplikasi
        if (!in_array($application->status, ['diterima', 'selesai'])) {
            return redirect()->back()->with('error', 'Status aplikasi tidak valid untuk pembuatan sertifikat.');
        }
        
        // Validasi status magang harus 'completed'
        if ($application->status_magang !== 'completed') {
            return redirect()->back()
                ->with('error', 'Status magang belum selesai. Silakan selesaikan magang terlebih dahulu.');
        }
        
        // Cek apakah sudah ada sertifikat untuk aplikasi ini
        if ($application->certificate) {
            return redirect()->route('admin.certificates.show', $application->certificate)
                ->with('info', 'Sertifikat sudah ada untuk aplikasi ini.');
        }
        
        // Validasi laporan bulanan
        if (!$application->hasCompletedAllReports()) {
            return redirect()->back()->with('error', 'Laporan bulanan belum lengkap atau belum disetujui semua.');
        }
        
        // Generate nomor sertifikat
        $certNumber = str_pad(Certificate::count() + 1, 4, '0', STR_PAD_LEFT);
        $year = date('Y');
        $certificateNumber = 'CERT/' . $year . '/' . $certNumber;
        
        // Buat nama file dan path
        $fileName = $certNumber . '.pdf';
        $relativePath = 'certificates/CERT/' . $year . '/' . $fileName;
        $fullPath = storage_path('app/public/' . $relativePath);
        
        // Pastikan direktori tujuan ada
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Ensure all relationships are loaded
        $application->load(['user.studentProfile', 'internship']);
        
        // Generate PDF content
        $pdf = PDF::loadView('admin.certificates.template', [
            'user' => $application->user,
            'application' => $application,
            'certificateNumber' => $certificateNumber,
            'issueDate' => now()->format('d F Y'),
            'startDate' => $application->internship->start_date,
            'endDate' => $application->internship->end_date
        ]);
        
        // Simpan PDF ke storage
        $pdf->save($fullPath);
        
        // Log penyimpanan file
        \Log::info('Menyimpan sertifikat', [
            'certificate_number' => $certificateNumber,
            'file_path' => $fullPath,
            'file_exists' => file_exists($fullPath) ? 'Ya' : 'Tidak',
            'directory' => $directory,
            'directory_exists' => is_dir($directory) ? 'Ya' : 'Tidak'
        ]);
        
        // Start database transaction
        $certificate = DB::transaction(function () use ($application, $relativePath, $certificateNumber) {
            // Create certificate record
            $certificate = Certificate::create([
                'user_id' => $application->user_id,
                'internship_id' => $application->internship_id,
                'application_id' => $application->id,
                'certificate_number' => $certificateNumber,
                'certificate_path' => $relativePath,
                'issue_date' => now(),
                'status' => Certificate::STATUS_PUBLISHED,
                'verified_at' => now(),
                'verified_by' => auth()->id()
            ]);
            
            // Update status magang jika belum 'completed'
            if ($application->status_magang !== 'completed') {
                $application->update(['status_magang' => 'completed']);
            }
            
            // Log the activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($certificate)
                ->withProperties([
                    'user_id' => $application->user_id,
                    'internship_id' => $application->internship_id,
                    'application_id' => $application->id,
                    'certificate_number' => $certificateNumber
                ])
                ->log('Menerbitkan sertifikat');
                
            return $certificate;
        });
        
        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Sertifikat berhasil diterbitkan.');
    }
    
    /**
     * Download a certificate
     */
    public function download(Certificate $certificate)
{
    $filePath = storage_path('app/public/' . $certificate->certificate_path);
    
    if (!file_exists($filePath)) {
        \Log::error('File sertifikat tidak ditemukan', [
            'certificate_id' => $certificate->id,
            'path' => $filePath
        ]);
        return back()->with('error', 'File sertifikat tidak ditemukan');
    }

    // Ganti semua tanda "/" dengan "_" pada nama file
    $namaFile = 'Sertifikat_' . str_replace(['/', '\\'], '_', $certificate->certificate_number) . '.pdf';

    return response()->download($filePath, $namaFile, [
        'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
        'Pragma' => 'no-cache',
        'Expires' => '0'
    ]);
}
    
    /**
     * Revoke a certificate
     */
    public function revoke(Request $request, Certificate $certificate)
    {
        try {
            $validated = $request->validate([
                'revoked_reason' => 'required|string|min:10|max:500'
            ], [
                'revoked_reason.required' => 'Alasan pencabutan wajib diisi',
                'revoked_reason.min' => 'Alasan pencabutan minimal :min karakter',
                'revoked_reason.max' => 'Alasan pencabutan maksimal :max karakter'
            ]);
            
            // Pastikan sertifikat belum dicabut sebelumnya
            if ($certificate->status === Certificate::STATUS_REVOKED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sertifikat ini sudah dicabut sebelumnya.'
                ], 422);
            }
            
            // Update status sertifikat
            $certificate->update([
                'status' => Certificate::STATUS_REVOKED,
                'revoked_at' => now(),
                'revoked_by' => auth()->id(),
                'revoked_reason' => $validated['revoked_reason']
            ]);
            
            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->performedOn($certificate)
                ->withProperties([
                    'reason' => $validated['revoked_reason']
                ])
                ->log('Mencabut sertifikat');
                
            return response()->json([
                'success' => true,
                'message' => 'Sertifikat berhasil dicabut.'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Gagal mencabut sertifikat: ' . $e->getMessage(), [
                'certificate_id' => $certificate->id,
                'user_id' => auth()->id(),
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencabut sertifikat: ' . $e->getMessage()
            ], 500);
        }
    }
}
