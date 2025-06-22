<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersReport;
use App\Models\ReportAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'mahasiswa']);
    }

    public function index()
    {
        $reports = UsersReport::where('user_id', Auth::id())
            ->with('attachments')
            ->latest()
            ->paginate(10); // Tambahkan pagination untuk performa yang lebih baik
            
        return view('mahasiswa.profile.index', compact('reports'));
    }

    public function create()
    {
        return view('mahasiswa.profile.index'); // Arahkan ke halaman yang sama dengan form
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $report = UsersReport::create([
                'user_id' => Auth::id(),
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'status' => 'dikirim',
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = str_replace('public/', '', $file->store('public/report_attachments'));
                    ReportAttachment::create([
                        'users_report_id' => $report->id,
                        'file_path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            DB::commit();
            return redirect()
                ->route('mahasiswa.profile.index', ['tab' => 'reports'])
                ->with('success', 'Laporan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $report = UsersReport::where('user_id', Auth::id())
            ->with('attachments')
            ->findOrFail($id);
            
        return view('mahasiswa.profile.index', compact('report'));
    }

    public function destroy($id)
    {
        try {
            $report = UsersReport::where('user_id', Auth::id())
                ->with('attachments')
                ->findOrFail($id);

            DB::beginTransaction();
            
            // Hapus file attachment dari storage dan database
            foreach ($report->attachments as $attachment) {
                if ($attachment->file_path && Storage::disk('public')->exists($attachment->file_path)) {
                    Storage::disk('public')->delete($attachment->file_path);
                }
                $attachment->delete();
            }

            $report->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus laporan: ' . $e->getMessage()
            ], 500);
        }
    }
}