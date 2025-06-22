<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Internship;
use App\Models\Application;
use App\Models\InternshipReport;
use App\Models\InternshipAttachment;
use App\Models\StudentProfile;
use App\Helpers\DivisionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Facades\Activity as ActivityLog;

class InternshipController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'mahasiswa'])->except(['index', 'show']);
    }

    /**
     * Show the list of internships.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Set locale ke Indonesia untuk Carbon
        Carbon::setLocale('id');
        $query = Internship::where('is_active', true);

        // Simpan parameter filter untuk form
        $filters = $request->only(['search', 'education', 'division']);
        $hasFilters = false;
        $hasSearch = !empty($filters['search']);
        $hasEducation = !empty($filters['education']);
        $hasDivision = !empty($filters['division']);

        // Apply search filter (supports partial match)
        if ($hasSearch) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('location', 'like', "%$search%");
            });
            $hasFilters = true;
        }

        // Apply education filter if provided
        if ($hasEducation) {
            $query->where('education_qualification', $filters['education']);
            $hasFilters = true;
        }

        // Apply division filter if provided
        if ($hasDivision) {
            $query->where('division', $filters['division']);
            $hasFilters = true;
        }

        // Get the results with applications count
        $featuredInternships = $query->withCount('applications')
            ->latest()
            ->paginate(6);
        
        // Add flash message if no results found
        if ($featuredInternships->isEmpty() && $hasFilters) {
            $message = 'Tidak ada lowongan yang tersedia';
            if ($hasSearch || $hasEducation || $hasDivision) {
                $message .= ' dengan filter yang dipilih.';
                $message .= ' Coba sesuaikan filter divisi atau pendidikan Anda.';
            }
            session()->flash('no_results', $message);
        }

        return view('internships.index', compact('featuredInternships', 'filters'));
    }

    /**
     * Show the internship details.
     *
     * @param  \App\Models\Internship  $internship
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Internship $internship)
    {
        // Check if user has applied (only if user is logged in)
        $hasApplied = false;
        $userDocuments = [];
        $hasCompleteDocuments = false;
        $isProfileComplete = false;
        $missingSections = [];
        
        if (Auth::check()) {
            $user = Auth::user();
            $hasApplied = Application::where('user_id', $user->id)
                ->where('internship_id', $internship->id)
                ->exists();
                
            // Get user's profile and check completeness
            $profile = $user->studentProfile;
            if ($profile) {
                // Get user documents
                $userDocuments = $profile->documents()->get()->keyBy('type');
                
                // Check if all required documents are present
                $requiredTypes = collect(['id_card', 'cv', 'transcript']);
                $userDocTypes = $userDocuments->pluck('type');
                $hasCompleteDocuments = $requiredTypes->diff($userDocTypes)->isEmpty();
                
                // Check profile completeness
                $missingSections = $this->checkProfileCompleteness($profile);
                $isProfileComplete = empty($missingSections);
            } else {
                $missingSections = ['profile' => 'Informasi pribadi belum dilengkapi'];
            }
        }
        
        return view('internships.show', compact(
            'internship', 
            'hasApplied',
            'userDocuments',
            'hasCompleteDocuments',
            'isProfileComplete',
            'missingSections'
        ));
    }

    /**
     * Apply for an internship.
     *
     * @param  \App\Models\Internship  $internship
     * @return \Illuminate\Http\RedirectResponse
     */
    public function apply(Internship $internship)
    {
        \Log::info('Apply method called', ['internship_id' => $internship->id]);
        $user = Auth::user();
        \Log::info('User info', ['user_id' => $user->id, 'email' => $user->email]);
        
        try {
            // 1. Check if internship is still open
            if (!$internship->isOpenForApplication()) {
                return redirect()->back()
                    ->with('error', 'Maaf, pendaftaran untuk lowongan ini sudah ditutup.');
            }
            
            // 2. Check if user has already applied
            $existingApplication = Application::where('user_id', $user->id)
                ->where('internship_id', $internship->id)
                ->first();
                
            if ($existingApplication) {
                $statusMessage = match($existingApplication->status) {
                    'pending' => 'Lamaran Anda sedang dalam proses review.',
                    'approved' => 'Lamaran Anda telah disetujui.',
                    'rejected' => 'Lamaran Anda sebelumnya ditolak.',
                    default => 'Anda sudah melamar untuk lowongan ini.'
                };
                return redirect()->route('mahasiswa.internships.show', $internship)
                    ->with('info', $statusMessage);
            }
            
            // 3. Check if profile is complete
            $profile = $user->studentProfile;
            
            if (!$profile) {
                return redirect()->route('mahasiswa.profile.index')
                    ->with('warning', 'Silakan lengkapi profil Anda terlebih dahulu sebelum melamar.');
            }
            
            if ($profile->profile_completion_percentage < 100) {
                return redirect()->route('mahasiswa.profile.index')
                    ->with('warning', 'Profil Anda belum lengkap. Silakan lengkapi profil terlebih dahulu sebelum melamar.');
            }
            
            // 4. Check if already applied
            $existingApplication = Application::where('user_id', $user->id)
                ->where('internship_id', $internship->id)
                ->first();
                
            if ($existingApplication) {
                return redirect()->back()
                    ->with('error', [
                        'title' => 'Gagal Mengirim Lamaran',
                        'message' => 'Anda sudah melamar untuk magang ini sebelumnya.'
                    ]);
            }
            
            // 5. Check if internship is still open
            if (!$internship->isOpenForApplication()) {
                return redirect()->back()
                    ->with('error', [
                        'title' => 'Pendaftaran Ditutup',
                        'message' => 'Maaf, pendaftaran untuk lowongan ini sudah ditutup.'
                    ]);
            }

            // 6. Check if there's available quota for acceptance
            if (!$internship->hasAvailableQuota()) {
                return redirect()->back()
                    ->with('error', [
                        'title' => 'Kuota Penuh',
                        'message' => 'Maaf, kuota penerimaan untuk lowongan ini sudah penuh.'
                    ]);
            }
            
            // 7. Check if already applied
            $existingApplication = Application::where('user_id', $user->id)
                ->where('internship_id', $internship->id)
                ->first();
                
            if ($existingApplication) {
                return redirect()->back()
                    ->with('error', [
                        'title' => 'Sudah Melamar',
                        'message' => 'Anda sudah melamar untuk lowongan ini sebelumnya.'
                    ]);
            }
            
            // 8. Create new application with 'terkirim' status
            $application = Application::create([
                'user_id' => $user->id,
                'internship_id' => $internship->id,
                'status' => 'terkirim',
                'applied_at' => now(),
                'notes' => 'Lamaran terkirim pada ' . now()->format('d F Y H:i')
            ]);
            
            // Log the application
            ActivityLog::causedBy($user)
                ->performedOn($internship)
                ->withProperties(['internship_id' => $internship->id])
                ->log('Mengajukan lamaran magang');
            
            // Create notification for user
            $user->notify(new \App\Notifications\ApplicationSubmitted($application));
            
            // Redirect to applications page with success message
            return redirect()->route('mahasiswa.applications')
                ->with('success', 'Lamaran Anda untuk ' . $internship->title . ' telah berhasil dikirim. Admin akan memproses lamaran Anda.');
                
        } catch (\Exception $e) {
            \Log::error('Error applying for internship: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengirim lamaran. Silakan coba lagi nanti.');
        }
    }

    /**
     * Display a listing of the user's applications.
     *
     * @return \Illuminate\View\View
     */
    public function applications()
    {
        // Get applications with eager loading
        $applications = Application::where('user_id', Auth::id())
            ->with(['internship' => function($query) {
                $query->select('id', 'title');
            }])
            ->with(['processedBy', 'approvedBy', 'rejectedBy']) // Load related users
            ->latest('created_at') // Changed from applied_at to created_at
            ->paginate(10);
        
        return view('mahasiswa.applications.index', compact('applications'));
    }

    /**
     * Display the specified application.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\View\View
     */
    public function showApplication(Application $application)
    {
        try {
            // Verify ownership
            if ($application->user_id !== Auth::id()) {
                abort(403);
            }

            // Eager load relationships
            $application->load([
                'internship',
                'internship.documents',
                'reports',
                'reports.attachments',
                'certificate'
            ]);

            return view('mahasiswa.applications.show', compact('application'));
            
        } catch (\Exception $e) {
            \Log::error('Error showing application: ' . $e->getMessage());
            return redirect()->route('mahasiswa.applications')
                ->with('error', [
                    'title' => 'Terjadi Kesalahan',
                    'message' => 'Gagal memuat detail lamaran. Silakan coba lagi nanti.'
                ]);
        }
    }

    public function storeApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $application = $profile->internshipApplications()->create($request->all());
        $profile->calculateCompletionPercentage();

        return response()->json([
            'success' => true,
            'message' => 'Lamaran magang berhasil diajukan',
            'application' => $application,
            'completion_percentage' => $profile->profile_completion_percentage
        ]);
    }

    public function updateApplication(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $application = $profile->internshipApplications()->findOrFail($id);
        $application->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lamaran magang berhasil diperbarui',
            'application' => $application
        ]);
    }

    public function storeReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'internship_application_id' => 'required|exists:internship_applications,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'report_date' => 'required|date',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $report = $profile->internshipReports()->create([
            'internship_application_id' => $request->internship_application_id,
            'title' => $request->title,
            'content' => $request->content,
            'report_date' => $request->report_date,
            'status' => 'submitted'
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalFilename = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('internship_attachments/' . Auth::id(), $filename, 'public');
                
                $report->attachments()->create([
                    'file_path' => $path,
                    'original_filename' => $originalFilename,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType
                ]);
            }
        }

        $profile->calculateCompletionPercentage();

        return response()->json([
            'success' => true,
            'message' => 'Laporan magang berhasil disimpan',
            'report' => $report->load('attachments'),
            'completion_percentage' => $profile->profile_completion_percentage
        ]);
    }

    public function updateReport(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'report_date' => 'required|date',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $report = $profile->internshipReports()->findOrFail($id);
        
        $report->update([
            'title' => $request->title,
            'content' => $request->content,
            'report_date' => $request->report_date,
            'status' => 'submitted'
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalFilename = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                $path = $file->storeAs('internship_attachments/' . Auth::id(), $filename, 'public');
                
                $report->attachments()->create([
                    'file_path' => $path,
                    'original_filename' => $originalFilename,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan magang berhasil diperbarui',
            'report' => $report->load('attachments')
        ]);
    }

    public function downloadAttachment($id)
    {
        $attachment = InternshipAttachment::findOrFail($id);
        
        // Verify ownership
        if ($attachment->internshipReport->application->user_id !== Auth::id()) {
            abort(403);
        }
        
        return Storage::download($attachment->file_path, $attachment->original_filename);
    }
    
    /**
     * Check if student profile is complete
     * 
     * @param \App\Models\StudentProfile $profile
     * @return array
     */
    protected function checkProfileCompleteness($profile)
    {
        $missingSections = [];
        
        // 1. Check Personal Information
        $requiredPersonalFields = [
            'full_name', 'nik', 'birth_place', 'birth_date', 'address'
        ];
        
        $missingFields = [];
        foreach ($requiredPersonalFields as $field) {
            if (empty($profile->$field) && $profile->$field !== '0' && $profile->$field !== 0) {
                $missingFields[] = $field;
            }
        }
        
        if (!empty($missingFields)) {
            \Log::debug('Missing personal fields:', [
                'profile_id' => $profile->id,
                'missing_fields' => $missingFields,
                'profile_data' => $profile->toArray()
            ]);
            $missingSections['personal'] = 'Informasi pribadi belum lengkap (Nama Lengkap, NIK, Tempat/Tanggal Lahir, Alamat)';
        }
        
        // 2. Check Academic Information
        if (!$profile->educations()->exists()) {
            $missingSections['education'] = 'Riwayat pendidikan belum diisi';
        }
        
        // 3. Check Skills
        if (!$profile->skills()->exists()) {
            $missingSections['skills'] = 'Keahlian belum diisi';
        }
        
        // 4. Check Family Information
        if (!$profile->familyMembers()->exists()) {
            $missingSections['family'] = 'Informasi keluarga belum diisi';
        }
        
        // 5. Check Required Documents
        $requiredDocs = ['id_card', 'cv', 'transcript'];
        $existingDocs = $profile->documents()->whereIn('type', $requiredDocs)
            ->pluck('type')
            ->toArray();
            
        $missingDocs = array_diff($requiredDocs, $existingDocs);
        
        if (!empty($missingDocs)) {
            $missingSections['documents'] = 'Dokumen wajib belum lengkap';
        }
        
        return $missingSections;
    }

    public function deleteAttachment($id)
    {
        $profile = StudentProfile::where('user_id', Auth::id())->firstOrFail();
        $attachment = InternshipAttachment::whereHas('report', function($query) use ($profile) {
            $query->where('student_profile_id', $profile->id);
        })->findOrFail($id);

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lampiran berhasil dihapus'
        ]);
    }

    /**
     * Handle document upload for internship application
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Internship  $internship
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadDocument(Request $request, Internship $internship)
    {
        $user = Auth::user();
        $profile = $user->studentProfile;
        
        // Validate request
        $validated = $request->validate([
            'document_type' => 'required|in:cv,transcript,id_card,photo,certificate',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048', // 2MB max
        ]);

        try {
            // Mulai database transaction
            DB::beginTransaction();

            // Hapus dokumen lama jika ada
            $existingDocument = DB::table('student_documents')
                ->where('student_profile_id', $profile->id)
                ->where('type', $request->document_type)
                ->first();

            if ($existingDocument) {
                // Hapus file lama dari storage
                Storage::disk('public')->delete($existingDocument->file_path);
                DB::table('student_documents')->where('id', $existingDocument->id)->delete();
            }

            // Simpan file baru
            $file = $request->file('document');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents/' . $user->id, $fileName, 'public');

            // Simpan ke tabel student_documents
            $documentId = DB::table('student_documents')->insertGetId([
                'student_profile_id' => $profile->id,
                'type' => $request->document_type,
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'mime_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diunggah',
                'document' => [
                    'id' => $documentId,
                    'type' => $request->document_type,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'created_at' => now()->format('d M Y H:i'),
                    'url' => route('mahasiswa.profile.view-document', $documentId),
                    'download_url' => route('mahasiswa.profile.download-document', $documentId)
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error uploading document: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage()
            ], 500);
        }
    }
}
