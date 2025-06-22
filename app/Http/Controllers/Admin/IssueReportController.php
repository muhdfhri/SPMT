<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UsersReport;
use App\Models\ReportAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class IssueReportController extends Controller
{
    /**
     * Menampilkan daftar laporan kendala
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $perPage = $request->input('per_page', 10);
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }
        
        // Validate sort field
        $validSortFields = ['created_at', 'name', 'status'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'created_at';
        }
        
        // Validate per_page value
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? (int)$perPage : 10;
        
        $reports = UsersReport::with(['user', 'admin', 'attachments'])
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($sortField === 'name', function($query) use ($sortDirection) {
                return $query->join('users', 'users_reports.user_id', '=', 'users.id')
                    ->orderBy('users.name', $sortDirection)
                    ->select('users_reports.*');
            }, function($query) use ($sortField, $sortDirection) {
                return $query->orderBy($sortField, $sortDirection);
            })
            ->paginate($perPage)
            ->withQueryString();
            
        return view('admin.issue-reports.index', compact('reports', 'sortField', 'sortDirection'));
    }

    /**
     * Menampilkan detail laporan kendala
     * 
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $report = UsersReport::with(['user', 'admin', 'attachments'])->findOrFail($id);
        return view('admin.issue-reports.show', compact('report'));
    }

    /**
     * Memperbarui status laporan kendala
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:dikirim,diproses,selesai,ditolak',
            'admin_notes' => 'nullable|string|max:1000',
        ]);
        
        $report = UsersReport::findOrFail($id);
        $report->status = $validated['status'];
        $report->admin_notes = $validated['admin_notes'] ?? null;
        $report->admin_id = Auth::id();
        
        if ($validated['status'] === 'selesai') {
            $report->resolved_at = now();
        }
        
        $report->save();
        
        return redirect()->route('admin.issue-reports.show', $report->id)
            ->with('success', 'Status laporan berhasil diperbarui');
    }

    /**
     * Menampilkan preview lampiran laporan kendala
     *
     * @param  int  $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function preview($attachmentId)
    {
        try {
            // Cari attachment berdasarkan ID
            $attachment = ReportAttachment::findOrFail($attachmentId);
            
            // Cek apakah user memiliki akses (opsional, sesuai kebutuhan)
            // $this->authorize('view', $attachment->report);
            
            // Coba beberapa kemungkinan path
            $possiblePaths = [
                $attachment->file_path,
                'public/' . $attachment->file_path,
                'public/' . ltrim($attachment->file_path, '/'),
                ltrim($attachment->file_path, 'public/'),
            ];
            
            $filePath = null;
            $diskToUse = 'local'; // default disk
            
            // Cek path mana yang ada
            foreach ($possiblePaths as $path) {
                if (!empty($path) && Storage::exists($path)) {
                    $filePath = $path;
                    break;
                }
            }
            
            // Jika tidak ditemukan, coba dengan disk public
            if (!$filePath) {
                foreach ($possiblePaths as $path) {
                    if (!empty($path) && Storage::disk('public')->exists($path)) {
                        $filePath = $path;
                        $diskToUse = 'public';
                        break;
                    }
                    
                    // Coba juga tanpa leading slash
                    $cleanPath = ltrim($path, '/');
                    if (!empty($cleanPath) && Storage::disk('public')->exists($cleanPath)) {
                        $filePath = $cleanPath;
                        $diskToUse = 'public';
                        break;
                    }
                }
            }
            
            if (!$filePath) {
                abort(404, 'File tidak ditemukan');
            }
            
            // Dapatkan file info
            $originalFilename = $attachment->original_filename ?? 'file';
            $mimeType = Storage::disk($diskToUse)->mimeType($filePath);
            $fileSize = Storage::disk($diskToUse)->size($filePath);
            
            // Tentukan apakah file harus di-display inline atau di-download
            $extension = strtolower(pathinfo($originalFilename, PATHINFO_EXTENSION));
            $inlineExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'txt', 'csv'];
            $disposition = in_array($extension, $inlineExtensions) ? 'inline' : 'attachment';
            
            // Return file response
            return Storage::disk($diskToUse)->response($filePath, $originalFilename, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => $disposition . '; filename="' . $originalFilename . '"',
                'Content-Length' => $fileSize,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error previewing attachment: ' . $e->getMessage(), [
                'attachment_id' => $attachmentId,
                'error' => $e->getTraceAsString()
            ]);
            
            abort(500, 'Terjadi kesalahan saat membuka file');
        }
    }
}
