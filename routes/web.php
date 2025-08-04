<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InternshipController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\Mahasiswa\LaporanController;
use App\Models\Application;
use Spatie\Activitylog\Models\Activity;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\NotificationController;

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

// Divisions page
Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');

// About Internship Program
Route::get('/about', [AboutController::class, 'index'])->name('about.index');

// Public internship routes
Route::get('/internships', [InternshipController::class, 'index'])->name('internships.index');
Route::get('/internships/{internship}', [InternshipController::class, 'show'])->name('internships.show');
Route::post('/internships/{internship}/upload-document', [InternshipController::class, 'uploadDocument'])->name('internships.upload-document');

// Internship application route
Route::post('/internships/{internship}/apply', [InternshipController::class, 'apply'])
    ->name('internships.apply')
    ->middleware(['auth', 'mahasiswa']);

// Test route without CSRF protection (for development only)
if (app()->environment('local')) {
    Route::post('/test-apply/{internship}', [InternshipController::class, 'apply'])
        ->name('internships.test-apply')
        ->withoutMiddleware(['web']);
}

Auth::routes();

// Add a general profile route that redirects to the appropriate profile page
Route::get('/profile', function() {
    if (Auth::check()) {
        if (Auth::user()->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.profile.index');
        }
        // Add other role redirects here if needed
    }
    return redirect('/');
})->name('profile');

// Mahasiswa Routes
// Test route for view debugging
Route::get('/test-view', function() {
    return view('mahasiswa.reports.edit', [
        'report' => \App\Models\MonthlyReport::first(),
        'monthNames' => [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ]
    ]);
})->middleware('auth');

Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentProfileController::class, 'index'])->name('index');
        Route::post('/personal-info', [StudentProfileController::class, 'updatePersonalInfo'])->name('update-personal-info');
        Route::post('/upload-photo', [StudentProfileController::class, 'uploadProfilePhoto'])->name('upload-photo');
        Route::post('/change-password', [StudentProfileController::class, 'changePassword'])->name('change-password');
        
        // Add the edit route here
        Route::get('/{profile}/edit', [StudentProfileController::class, 'edit'])->name('edit');
        
        // Education routes
        Route::post('/education', [StudentProfileController::class, 'storeEducation'])->name('store-education');
        Route::put('/education/{id}', [StudentProfileController::class, 'updateEducation'])->name('update-education');
        Route::delete('/education/{id}', [StudentProfileController::class, 'deleteEducation'])->name('delete-education');
        
        // Experience routes
        Route::post('/experience', [StudentProfileController::class, 'storeExperience'])->name('store-experience');
        Route::put('/experience/{id}', [StudentProfileController::class, 'updateExperience'])->name('update-experience');
        Route::delete('/experience/{id}', [StudentProfileController::class, 'deleteExperience'])->name('delete-experience');
        
        // Award routes
        Route::post('/award', [StudentProfileController::class, 'storeAward'])->name('store-award');
        
        // Application routes
        Route::get('/applications/{application}', [App\Http\Controllers\InternshipController::class, 'showApplication'])
            ->name('applications.show');
        Route::put('/award/{id}', [StudentProfileController::class, 'updateAward'])->name('update-award');
        Route::delete('/award/{id}', [StudentProfileController::class, 'deleteAward'])->name('delete-award');
        
        // Skill routes
        Route::post('/skill', [StudentProfileController::class, 'storeSkill'])->name('store-skill');
        Route::put('/skill/{id}', [StudentProfileController::class, 'updateSkill'])->name('update-skill');
        Route::delete('/skill/{id}', [StudentProfileController::class, 'deleteSkill'])->name('delete-skill');
        
        // Document routes
        Route::post('/document', [StudentProfileController::class, 'storeDocument'])->name('store-document');
        Route::put('/document/{id}', [StudentProfileController::class, 'updateDocument'])->name('update-document');
        Route::delete('/document/{id}', [StudentProfileController::class, 'deleteDocument'])->name('delete-document');
        Route::get('/document/{id}/download', [StudentProfileController::class, 'downloadDocument'])->name('download-document');
        Route::get('/document/{id}/view', [StudentProfileController::class, 'viewDocument'])->name('view-document');
        
        // Family member routes
        Route::post('/family-member', [StudentProfileController::class, 'storeFamilyMember'])->name('store-family-member');
        Route::put('/family-member/{id}', [StudentProfileController::class, 'updateFamilyMember'])->name('update-family-member');
        Route::delete('/family-member/{id}', [StudentProfileController::class, 'deleteFamilyMember'])->name('delete-family-member');
        
        // Profile completion status
        Route::get('/get-profile-completion-status', [StudentProfileController::class, 'getProfileCompletionStatus'])->name('get-profile-completion-status');
        
        // Update multiple documents
        Route::post('/update-documents', [StudentProfileController::class, 'updateDocuments'])->name('update-documents');
        
        // Update family info
        Route::post('/update-family-info', [StudentProfileController::class, 'updateFamilyInfo'])->name('update-family-info');
        
        // Update guardian info
        Route::post('/update-guardian-info', [StudentProfileController::class, 'updateGuardianInfo'])->name('update-guardian-info');
    });

    
    // My Applications
    Route::get('/applications', [InternshipController::class, 'applications'])
        ->name('applications');
    
    // Show Application Details
    Route::get('/applications/{application}', [InternshipController::class, 'showApplication'])
        ->name('applications.show')
        ->middleware(['auth', 'verified', 'role:mahasiswa']);
        
    // Report Routes
    Route::middleware('check_active_internship')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
        
        // Route untuk menghapus lampiran
        Route::delete('/reports/attachments/{attachment}', [ReportController::class, 'destroyAttachment'])
            ->name('reports.attachments.destroy');
        
        // Certificate Routes
        Route::get('/certificates', [\App\Http\Controllers\Mahasiswa\CertificateController::class, 'index'])->name('certificates.index');
        Route::get('/certificates/{certificate}', [\App\Http\Controllers\Mahasiswa\CertificateController::class, 'show'])->name('certificates.show');
        Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\Mahasiswa\CertificateController::class, 'download'])->name('certificates.download');
    });

    // Laporan Kendala
    Route::resource('laporan', \App\Http\Controllers\Mahasiswa\LaporanController::class)
    ->names('laporan')
    ->middleware(['auth', 'mahasiswa']);

    // Internship Routes
    Route::get('/internships', [InternshipController::class, 'index'])->name('internships.index');
    Route::get('/internships/{internship}', [InternshipController::class, 'show'])->name('internships.show');
});

// Log Activity Routes (for dashboard only)
Route::middleware(['auth', 'role:admin'])->prefix('admin/logs')->name('admin.logs.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
});

// Route untuk pengecekan aplikasi (sementara)
Route::get('/check-applications', function() {
    $applications = Application::where('status', 'diterima')
        ->select('id', 'status', 'status_magang', 'user_id', 'internship_id')
        ->with([
            'user' => function($q) {
                $q->select('id', 'name', 'email');
            },
            'internship' => function($q) {
                $q->select('id', 'start_date', 'end_date');
            }
        ])
        ->get();
    
    return response()->json($applications, 200, [], JSON_PRETTY_PRINT);
})->middleware(['auth', 'role:admin']);

// Admin Routes - Bagian yang perlu diperbaiki
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Update status magang
    Route::post('/applications/{application}/update-status', [\App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])
        ->name('applications.update-status');
        
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Internship Management
    Route::resource('internships', 'App\Http\Controllers\Admin\InternshipController');
    
    // Applications
    Route::resource('applications', \App\Http\Controllers\Admin\ApplicationController::class)
        ->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::post('/applications/{application}/process', [\App\Http\Controllers\Admin\ApplicationController::class, 'process'])
        ->name('applications.process');
    Route::post('/applications/{application}/approve', [\App\Http\Controllers\Admin\ApplicationController::class, 'approve'])
        ->name('applications.approve');
    Route::post('/applications/{application}/reject', [\App\Http\Controllers\Admin\ApplicationController::class, 'reject'])
        ->name('applications.reject');
    
    // Start Internship
    Route::post('/applications/{application}/start-internship', ['as' => 'applications.start-internship', 'uses' => 'App\Http\Controllers\Admin\ApplicationController@startInternship']);
    
    // Reports
    Route::resource('reports', \App\Http\Controllers\Admin\ReportController::class)->except(['create', 'store', 'edit', 'destroy']);
    Route::post('/reports/{report}/approve', [\App\Http\Controllers\Admin\ReportController::class, 'approve'])->name('reports.approve');
    Route::post('/reports/{report}/reject', [\App\Http\Controllers\Admin\ReportController::class, 'reject'])->name('reports.reject');
    Route::put('/reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'update'])->name('reports.update');
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('edit');
        Route::put('/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('password.update');
    });
    
    // Certificates
    Route::resource('certificates', \App\Http\Controllers\Admin\CertificateController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::post('/certificates/{user}/generate', [\App\Http\Controllers\Admin\CertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\Admin\CertificateController::class, 'download'])->name('certificates.download');
    Route::post('/certificates/{certificate}/revoke', [\App\Http\Controllers\Admin\CertificateController::class, 'revoke'])->name('certificates.revoke');
    
    // Students Management
    Route::resource('students', \App\Http\Controllers\Admin\StudentController::class)->only(['index', 'show']);
    
    // Active Internships
    Route::prefix('active-internships')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\StudentController::class, 'activeInternships'])
            ->name('students.active-internships');
            
        Route::post('/applications/{application}/complete', [\App\Http\Controllers\Admin\StudentController::class, 'completeInternship'])
            ->name('applications.complete');
    });
    
    // Student Applications
    Route::get('students/{student}/applications', [\App\Http\Controllers\Admin\StudentController::class, 'applications'])
        ->name('students.applications');
        
    // Student Reports
    Route::get('students/{student}/reports', [\App\Http\Controllers\Admin\StudentController::class, 'reports'])
        ->name('students.reports');
        
    // Student Certificates
    Route::get('students/{student}/certificates', [\App\Http\Controllers\Admin\StudentController::class, 'certificates'])
        ->name('students.certificates');
        
    // Student Documents
    Route::get('students/{student}/documents', [\App\Http\Controllers\Admin\StudentController::class, 'documents'])
        ->name('students.documents');
    Route::get('students/{student}/documents/{document}/download', [\App\Http\Controllers\Admin\StudentController::class, 'downloadDocument'])
        ->name('students.documents.download');
    
    // Activity Log
    Route::get('/activities', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities.index');
    
    // Issue Reports (Laporan Kendala)
    Route::resource('issue-reports', \App\Http\Controllers\Admin\IssueReportController::class)->only(['index', 'show', 'update']);
    
    // PERBAIKAN: Route preview attachment - pindahkan ke dalam group yang sama
    Route::get('issue-reports/preview/{attachment}', [\App\Http\Controllers\Admin\IssueReportController::class, 'preview'])
        ->name('issue-reports.preview');
});

// Notifikasi
Route::middleware('auth')->group(function () {
    // Web Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // API Routes
    Route::prefix('api')->group(function () {
        Route::get('/notifications', [NotificationController::class, 'apiIndex'])->name('api.notifications.index');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'apiMarkAsRead'])->name('api.notifications.read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'apiMarkAllAsRead'])->name('api.notifications.mark-all-read');
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
    });
});

// Internship routes
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/internship', [InternshipController::class, 'index'])->name('internship.index');
    Route::post('/internship/application', [InternshipController::class, 'storeApplication'])->name('internship.store-application');
    Route::put('/internship/application/{id}', [InternshipController::class, 'updateApplication'])->name('internship.update-application');
    Route::post('/internship/report', [InternshipController::class, 'storeReport'])->name('internship.store-report');
    Route::put('/internship/report/{id}', [InternshipController::class, 'updateReport'])->name('internship.update-report');
    Route::get('/internship/attachment/{id}/download', [InternshipController::class, 'downloadAttachment'])->name('internship.download-attachment');
    Route::delete('/internship/attachment/{id}', [InternshipController::class, 'deleteAttachment'])->name('internship.delete-attachment');
});

// Redirect authenticated users to the appropriate dashboard
Route::get('/home', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        }
        // Add other role redirects here if needed
    }
    return redirect('/');
})->name('home');

// Student Profile Routes - Consolidated into the main mahasiswa group above