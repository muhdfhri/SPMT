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
        
        // Filter berdasarkan kualifikasi pendidikan
        if ($request->has('education') && !empty($request->education)) {
            $query->where('education_qualification', $request->education);
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
            'division' => 'nullable|string|max:100',
            'education_qualification' => 'required|in:SMA/SMK,Vokasi,S1',
            'is_active' => 'sometimes|boolean',
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
                'division' => $request->division,
                'education_qualification' => $request->education_qualification,
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
    public function update(Request $request, $id)
    {
        \Log::info('Mengupdate data lowongan magang ID: ' . $id);
        \Log::info('Data yang diterima:', $request->all());

        // Validasi data
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'division' => 'nullable|string|max:100',
            'education_qualification' => 'required|in:SMA/SMK,Vokasi,S1',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            \Log::error('Validasi gagal:', $validator->errors()->all());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Cari data yang akan diupdate
            $internship = Internship::findOrFail($id);

            // Update data langsung tanpa menggunakan mass assignment
            $internship->title = $request->title;
            $internship->description = $request->description;
            $internship->requirements = $request->requirements;
            $internship->location = $request->location;
            $internship->start_date = $request->start_date;
            $internship->end_date = $request->end_date;
            $internship->division = $request->division;
            $internship->education_qualification = $request->education_qualification;
            $internship->is_active = $request->has('is_active') ? true : false;
            
            // Simpan perubahan
            $internship->save();
            
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

        try {
            $internship->delete();
            return redirect()->route('admin.internships.index')
                ->with('success', 'Lowongan magang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus lowongan: ' . $e->getMessage());
        }
    }
}
