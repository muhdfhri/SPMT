<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InternshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Internship::query();
        
        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
            });
        }
        
        // Filter berdasarkan kualifikasi pendidikan
        if ($request->has('education') && !empty($request->education)) {
            $query->where('education_qualification', $request->education);
        }
        
        // Filter berdasarkan divisi
        if ($request->has('division') && !empty($request->division)) {
            $query->where('division', $request->division);
        }
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== '') {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }
        
        $internships = $query->latest()->paginate(10)->withQueryString();
        
        return view('admin.internships.index', compact('internships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.internships.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Mencoba menyimpan data lowongan magang baru');
        \Log::info('Data yang diterima:', $request->all());

        // Validasi data
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'application_deadline' => 'required|date|after_or_equal:today|before_or_equal:end_date',
            'division' => 'nullable|string|max:100',
            'education_qualification' => 'required|in:SMA/SMK,D3,S1,S2',
            'is_active' => 'sometimes|boolean',
        ], [
            'application_deadline.before_or_equal' => 'Batas pendaftaran harus sebelum atau sama dengan tanggal berakhir magang.',
            'application_deadline.after_or_equal' => 'Batas pendaftaran tidak boleh di masa lalu.'
        ]);

        if ($validator->fails()) {
            \Log::error('Validasi gagal:', $validator->errors()->all());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Siapkan data untuk disimpan
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'application_deadline' => $request->application_deadline,
                'division' => $request->division,
                'education_qualification' => $request->education_qualification,
                'quota' => (int) $request->quota, // Pastikan bertipe integer
                'is_active' => $request->has('is_active') ? true : false,
            ];

            \Log::info('Data yang akan disimpan:', $data);

            // Simpan data
            $internship = Internship::create($data);
            
            DB::commit();

            \Log::info('Lowongan magang berhasil disimpan dengan ID: ' . $internship->id);

            return redirect()->route('admin.internships.index')
                ->with('success', 'Lowongan magang berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal menyimpan lowongan magang:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Internship $internship)
    {
        $internship->loadCount('applications');
        return view('admin.internships.show', compact('internship'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Internship $internship)
    {
        return view('admin.internships.edit', compact('internship'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Internship $internship)
    {
        \Log::info('=== MULAI UPDATE LOWONGAN MAGANG ===');
        \Log::info('Mengupdate data lowongan magang ID: ' . $internship->id);
        \Log::info('Data yang diterima:', $request->all());
        \Log::info('Data sebelum update:', $internship->toArray());

        // Validasi data
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($request) {
                    if (strtotime($value) > strtotime($request->end_date)) {
                        $fail('Tanggal mulai tidak boleh melebihi tanggal berakhir.');
                    }
                },
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
            'application_deadline' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:end_date',
            ],
            'division' => 'nullable|string|max:100',
            'education_qualification' => 'required|in:SMA/SMK,Vokasi,S1',
            'quota' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ], [
            'quota.min' => 'Kuota minimal 1 peserta.',
            'quota.required' => 'Kuota harus diisi.',
            'quota.integer' => 'Kuota harus berupa angka.',
            'start_date.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'end_date.after_or_equal' => 'Tanggal berakhir harus setelah atau sama dengan tanggal mulai.',
            'application_deadline.before_or_equal' => 'Batas pendaftaran harus sebelum atau sama dengan tanggal berakhir magang.',
            'application_deadline.after_or_equal' => 'Batas pendaftaran tidak boleh kurang dari hari ini.'
        ]);

        if ($validator->fails()) {
            \Log::error('Validasi gagal:', $validator->errors()->all());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Log data sebelum update
            \Log::info('Data sebelum update:', $internship->toArray());
            \Log::info('Data yang akan diupdate:', $request->except(['_token', '_method']));

            try {
                // Update data
                $internship->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'requirements' => $request->requirements,
                    'location' => $request->location,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'application_deadline' => $request->application_deadline,
                    'division' => $request->division,
                    'education_qualification' => $request->education_qualification,
                    'quota' => (int) $request->quota,
                    'is_active' => $request->has('is_active') ? true : false,
                ]);
                
                \Log::info('Data berhasil diupdate:', $internship->fresh()->toArray());
            } catch (\Exception $e) {
                \Log::error('Gagal menyimpan perubahan:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
            
            DB::commit();

            \Log::info('Lowongan magang berhasil diupdate dengan ID: ' . $internship->id);

            return redirect()->route('admin.internships.index')
                ->with('success', 'Lowongan magang berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal mengupdate lowongan magang:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Internship $internship)
    {
        if ($internship->applications()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus lowongan yang sudah memiliki pendaftar');
        }

        $internship->delete();

        return redirect()->route('admin.internships.index')
            ->with('success', 'Lowongan magang berhasil dihapus');
    }
}
