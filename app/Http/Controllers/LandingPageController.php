<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Internship;
use Carbon\Carbon;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman landing page
     *
     * @return \Illuminate\View\View
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
            ->paginate(3);
        
        // Add flash message if no results found
        if ($featuredInternships->isEmpty() && $hasFilters) {
            $message = 'Tidak ada lowongan yang tersedia';
            if ($hasSearch || $hasEducation || $hasDivision) {
                $message .= ' dengan filter yang dipilih.';
                $message .= ' Coba sesuaikan filter divisi atau pendidikan Anda.';
            }
            session()->flash('no_results', $message);
        }

        return view('landingpage', compact('featuredInternships', 'filters'));
    }
}
