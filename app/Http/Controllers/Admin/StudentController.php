<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $students = QueryBuilder::for(User::where('role', User::ROLE_MAHASISWA))
            ->with(['studentProfile', 'applications' => function($query) {
                $query->latest()->first();
            }])
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('studentProfile', function($q) use ($search) {
                          $q->where('nim', 'like', "%{$search}%")
                            ->orWhere('study_program', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        
        return view('admin.students.index', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $student)
    {
        if (!$student->isMahasiswa()) {
            abort(404);
        }
        
        $student->load([
            'studentProfile',
            'applications' => function($query) {
                $query->with(['internship', 'reports', 'certificate'])
                      ->latest();
            },
            'documents'
        ]);
        
        return view('admin.students.show', compact('student'));
    }
    
    /**
     * Display a listing of the student's applications.
     */
    public function applications(User $student)
    {
        if (!$student->isMahasiswa()) {
            abort(404);
        }
        
        $applications = $student->applications()
            ->with(['internship', 'reports', 'certificate'])
            ->latest()
            ->paginate(10);
            
        return view('admin.students.applications', [
            'student' => $student,
            'applications' => $applications
        ]);
    }
    
    /**
     * Display a listing of the student's reports.
     */
    public function reports(User $student)
    {
        if (!$student->isMahasiswa()) {
            abort(404);
        }
        
        $reports = $student->reports()
            ->with(['application.internship'])
            ->latest()
            ->paginate(10);
            
        return view('admin.students.reports', [
            'student' => $student,
            'reports' => $reports
        ]);
    }
    
    /**
     * Display a listing of the student's certificates.
     */
    public function certificates(User $student)
    {
        if (!$student->isMahasiswa()) {
            abort(404);
        }
        
        $certificates = $student->certificates()
            ->with(['application.internship.company'])
            ->latest()
            ->paginate(10);
            
        return view('admin.students.certificates', [
            'student' => $student,
            'certificates' => $certificates
        ]);
    }
    
    /**
     * Display a listing of the student's documents.
     */
    public function documents(User $student)
    {
        if (!$student->isMahasiswa()) {
            abort(404);
        }
        
        $documents = $student->documents()
            ->latest()
            ->paginate(10);
            
        return view('admin.students.documents', [
            'student' => $student,
            'documents' => $documents
        ]);
    }
    
    /**
     * Display a listing of students with active internships.
     */
    public function activeInternships(Request $request)
    {
        $search = $request->input('search');
        $division = $request->input('division');
        $month = $request->input('month');
        $perPage = $request->input('per_page', 10) === 'all' ? PHP_INT_MAX : $request->input('per_page', 10);
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        $query = User::whereHas('applications', function($query) use ($division, $month) {
            $query->where('status', 'diterima')
                  ->whereIn('status_magang', ['menunggu', 'diterima', 'in_progress']);
                  
            if ($division && $division !== 'all') {
                $query->whereHas('internship', function($q) use ($division) {
                    $q->where('division', $division);
                });
            }
            
            if ($month) {
                $query->whereHas('internship', function($q) use ($month) {
                    $q->whereMonth('start_date', '<=', date('m', strtotime($month)))
                      ->whereMonth('end_date', '>=', date('m', strtotime($month)))
                      ->whereYear('start_date', '<=', date('Y', strtotime($month)))
                      ->whereYear('end_date', '>=', date('Y', strtotime($month)));
                });
            }
        });
        
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
        
        // Apply sorting
        switch ($sortField) {
            case 'name':
                $query->orderBy('name', $sortDirection);
                break;
                
            case 'nik':
                $query->join('student_profiles', 'users.id', '=', 'student_profiles.user_id')
                      ->orderBy('student_profiles.nik', $sortDirection)
                      ->select('users.*');
                break;
                
            case 'division':
                $query->join('applications', 'users.id', '=', 'applications.user_id')
                      ->join('internships', 'applications.internship_id', '=', 'internships.id')
                      ->where('applications.status', 'diterima')
                      ->whereIn('applications.status_magang', ['menunggu', 'diterima', 'in_progress'])
                      ->orderBy('internships.division', $sortDirection)
                      ->select('users.*')
                      ->distinct();
                break;
                
            case 'start_date':
                $query->join('applications', 'users.id', '=', 'applications.user_id')
                      ->join('internships', 'applications.internship_id', '=', 'internships.id')
                      ->where('applications.status', 'diterima')
                      ->whereIn('applications.status_magang', ['menunggu', 'diterima', 'in_progress'])
                      ->orderBy('internships.start_date', $sortDirection)
                      ->select('users.*')
                      ->distinct();
                break;
                
            default:
                $query->orderBy('users.created_at', $sortDirection);
        }
        
        // Eager load relationships
        $students = $query->with(['studentProfile' => function($query) {
                $query->select('user_id', 'full_name', 'nik', 'profile_photo');
            }])
            ->addSelect(['student_nik' => function($query) {
                $query->select('nik')
                    ->from('student_profiles')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1);
            }])
            ->with(['applications' => function($query) use ($division, $month) {
                $query->where('status', 'diterima')
                      ->whereIn('status_magang', ['menunggu', 'diterima', 'in_progress'])
                      ->with(['internship' => function($q) {
                          $q->select('id', 'start_date', 'end_date', 'title', 'division');
                      }])
                      ->when($division && $division !== 'all', function($q) use ($division) {
                          $q->whereHas('internship', function($q) use ($division) {
                              $q->where('division', $division);
                          });
                      })
                      ->when($month, function($q) use ($month) {
                          $q->whereHas('internship', function($q) use ($month) {
                              $q->whereMonth('start_date', '<=', date('m', strtotime($month)))
                                ->whereMonth('end_date', '>=', date('m', strtotime($month)))
                                ->whereYear('start_date', '<=', date('Y', strtotime($month)))
                                ->whereYear('end_date', '>=', date('Y', strtotime($month)));
                          });
                      })
                      ->latest();
            }])
            ->paginate($perPage)
            ->withQueryString();
        
        return view('admin.students.active-internships', compact('students'));
    }
    
    /**
     * Download the specified document.
     */
    public function downloadDocument(User $student, $documentId)
    {
        if (!$student->isMahasiswa()) {
            abort(404);
        }
        
        $document = $student->documents()->findOrFail($documentId);
        
        if (!Storage::exists($document->path)) {
            abort(404);
        }
        
        return Storage::download($document->path, $document->original_name);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Mark internship as completed.
     */
    public function completeInternship(Application $application)
    {
        // Debug log untuk memeriksa status magang
        \Log::info('Complete Internship Check', [
            'application_id' => $application->id,
            'current_status' => $application->status_magang,
            'has_completed_reports' => $application->hasCompletedAllReports(),
            'internship' => [
                'start_date' => $application->internship->start_date ?? null,
                'end_date' => $application->internship->end_date ?? null,
            ],
            'reports' => [
                'total' => $application->monthlyReports->count(),
                'approved' => $application->monthlyReports->where('status', 'approved')->count(),
            ]
        ]);

        // Pastikan aplikasi memiliki status magang 'in_progress' dan semua laporan sudah disetujui
        if ($application->status_magang !== 'in_progress') {
            return response()->json([
                'success' => false,
                'message' => 'Status magang tidak valid atau sudah selesai.'
            ], 400);
        }

        // Pastikan semua laporan sudah disetujui
        if (!$application->hasCompletedAllReports()) {
            $expectedMonths = $application->calculateExpectedMonths();
            $approvedCount = $application->monthlyReports()->where('status', 'approved')->count();
            $rejectedCount = $application->monthlyReports()->where('status', 'rejected')->count();
            
            $message = "Belum semua laporan disetujui ($approvedCount/$expectedMonths).";
            if ($rejectedCount > 0) {
                $message .= " Terdapat $rejectedCount laporan yang ditolak.";
            }
            $message .= " Harap setujui semua laporan terlebih dahulu.";
            
            return response()->json([
                'success' => false,
                'message' => $message,
                'details' => [
                    'expected_months' => $expectedMonths,
                    'approved_reports' => $approvedCount,
                    'rejected_reports' => $rejectedCount
                ]
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Update status magang menjadi 'completed' (tanpa underscore)
            $application->update([
                'status_magang' => 'completed',
                'completed_at' => now()
            ]);

            // Log aktivitas
            activity()
                ->causedBy(auth()->user())
                ->performedOn($application)
                ->log('menyelesaikan magang');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Magang berhasil ditandai selesai.',
                'redirect' => route('admin.students.active-internships')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal menandai magang selesai: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menandai magang selesai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
