@extends('layouts.admin')

@section('title', 'Manajemen Lowongan Magang - Admin Panel')

@push('styles')
<style>
    .internship-card {
        transition: all 0.3s ease;
    }
    .internship-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Daftar Lowongan Magang</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola seluruh lowongan magang yang tersedia</p>
            </div>
            <div>
                <a href="{{ route('admin.internships.create') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Lowongan
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800 flex items-center" role="alert">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800 flex items-center" role="alert">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Lowongan</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" placeholder="Cari judul atau lokasi..." 
                           value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                           onkeyup="debounceFilter()">
                </div>
            </div>
            <div class="relative">
                <label for="filter-education" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tingkat Pendidikan</label>
                <select id="filter-education" name="education" onchange="filterInternships()"
                    class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white appearance-none">
                    <option value="">Semua Pendidikan</option>
                    <option value="SMA/SMK" {{ request('education') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                    <option value="Vokasi" {{ request('education') == 'Vokasi' ? 'selected' : '' }}>Vokasi (D1/D2/D3/D4)</option>
                    <option value="S1" {{ request('education') == 'S1' ? 'selected' : '' }}>Sarjana (S1)</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400" style="top: 1.6rem;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            <div class="relative">
                <label for="filter-division" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Divisi</label>
                <select id="filter-division" name="division" onchange="filterInternships()"
                    class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white appearance-none">
                    <option value="">Semua Divisi</option>
                    @foreach(\App\Helpers\DivisionHelper::getAllDivisions() as $division)
                        <option value="{{ $division }}" {{ request('division') == $division ? 'selected' : '' }}>{{ $division }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400" style="top: 1.6rem;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            <div class="relative">
                <label for="filter-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select id="filter-status" name="status" onchange="filterInternships()"
                    class="block w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white appearance-none">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400" style="top: 1.6rem;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="resetFilters()" 
                        class="w-full h-[42px] px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    @if($internships->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($internships as $internship)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 internship-card relative">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-1">{{ $internship->title }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $internship->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $internship->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $internship->location }}</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $internship->division ?? 'Tidak ada divisi' }}</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $internship->education_qualification }}
                                </span>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    Durasi: {{ $internship->getDurationInMonths() }} Bulan
                                </span>
                            </div>
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-gray-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                @php
                                    // Hitung jumlah aplikasi yang sudah diterima
                                    $acceptedCount = $internship->applications()
                                        ->where('status_magang', 'diterima')
                                        ->count();
                                    $remainingQuota = $internship->quota - $acceptedCount;
                                @endphp
                                <span class="text-sm text-gray-600 dark:text-gray-300">
                                    Kuota: {{ $remainingQuota }} / {{ $internship->quota }} Orang
                                </span>
                            </div>
                        </div>

                        @if($internship->application_deadline)
                            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-300">Batas Pendaftaran:</span>
                                    <span class="font-medium {{ $internship->isOpenForApplication() ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $internship->application_deadline->format('d M Y') }}
                                    </span>
                                </div>
                                @if($internship->isOpenForApplication())
                                    @php
                                        // Menggunakan startOfDay() untuk memastikan perhitungan tanggal yang akurat
                                        $daysLeft = now()->startOfDay()->diffInDays(
                                            \Carbon\Carbon::parse($internship->application_deadline)->startOfDay(),
                                            false // Parameter false untuk menghindari nilai negatif
                                        );
                                    @endphp
                                    <div class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                                        Sisa waktu: {{ $daysLeft }} hari
                                    </div>
                                @else
                                    <div class="mt-1 text-xs text-red-600 dark:text-red-400">
                                        Pendaftaran ditutup
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Diperbarui {{ $internship->updated_at->diffForHumans() }}
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.internships.show', $internship) }}" 
                                   class="action-btn p-2 rounded-full text-blue-600 hover:bg-blue-50 dark:hover:bg-gray-700"
                                   data-tooltip="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.internships.edit', $internship) }}" 
                                   class="action-btn p-2 rounded-full text-indigo-600 hover:bg-indigo-50 dark:hover:bg-gray-700"
                                   data-tooltip="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.internships.destroy', $internship) }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="action-btn p-2 rounded-full text-red-600 hover:bg-red-50 dark:hover:bg-gray-700"
                                            data-tooltip="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-100 dark:border-gray-700 mt-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 md:mb-0">
                    Menampilkan <span class="font-medium">{{ $internships->firstItem() }}</span> sampai <span class="font-medium">{{ $internships->lastItem() }}</span> dari <span class="font-medium">{{ $internships->total() }}</span> lowongan
                </div>
                <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                    {{-- Tombol Previous --}}
                    @if($internships->onFirstPage())
                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $internships->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    @endif
                    
                    {{-- Nomor Halaman --}}
                    <div class="flex space-x-1">
                        @php
                            $currentPage = $internships->currentPage();
                            $lastPage = $internships->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                            
                            if ($startPage > 1) {
                                echo '<a href="' . $internships->url(1) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($currentPage == 1 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">1</a>';
                                if ($startPage > 2) {
                                    echo '<span class="px-3 py-1.5">...</span>';
                                }
                            }
                            
                            for ($page = $startPage; $page <= $endPage; $page++) {
                                $isCurrent = $page == $currentPage;
                                echo '<a href="' . $internships->url($page) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($isCurrent ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">' . $page . '</a>';
                            }
                            
                            if ($endPage < $lastPage) {
                                if ($endPage < $lastPage - 1) {
                                    echo '<span class="px-3 py-1.5">...</span>';
                                }
                                echo '<a href="' . $internships->url($lastPage) . '" class="px-3 py-1.5 border rounded-lg text-sm font-medium ' . ($currentPage == $lastPage ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700') . '">' . $lastPage . '</a>';
                            }
                        @endphp
                    </div>
                    
                    {{-- Tombol Next --}}
                    @if($internships->hasMorePages())
                        <a href="{{ $internships->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-600 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
            <div class="mx-auto w-16 h-16 text-gray-400 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                @if(request()->has('search') && !empty(request('search')))
                    Tidak ditemukan lowongan dengan kata kunci "{{ request('search') }}"
                @else
                    Belum ada lowongan magang
                @endif
            </h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                @if(request()->has('search') && !empty(request('search')))
                    Coba gunakan kata kunci lain atau hapus pencarian untuk melihat semua lowongan.
                @else
                    Silakan tambahkan lowongan magang baru untuk memulai.
                @endif
            </p>
            @if(!request()->has('search') || empty(request('search')))
                <a href="{{ route('admin.internships.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Lowongan Baru
                </a>
            @else
                <a href="{{ route('admin.internships.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                    Tampilkan Semua Lowongan
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Debounce function to limit how often the search function is called
let debounceTimer;
function debounceFilter(delay = 500) {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(filterInternships, delay);
}

function filterInternships() {
    const search = document.getElementById('search').value;
    const education = document.getElementById('filter-education').value;
    const division = document.getElementById('filter-division').value;
    const status = document.getElementById('filter-status').value;
    
    const url = new URL(window.location.href);
    
    // Reset to first page when filtering
    url.searchParams.set('page', '1');
    
    // Set or remove search parameter
    if (search) {
        url.searchParams.set('search', search);
    } else {
        url.searchParams.delete('search');
    }
    
    // Set or remove education parameter
    if (education) {
        url.searchParams.set('education', education);
    } else {
        url.searchParams.delete('education');
    }
    
    // Set or remove division parameter
    if (division) {
        url.searchParams.set('division', division);
    } else {
        url.searchParams.delete('division');
    }
    
    // Set or remove status parameter
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    window.location.href = url.toString();
}

// Function to reset all filters
function resetFilters() {
    // Redirect to the base URL without any query parameters
    window.location.href = "{{ route('admin.internships.index') }}";
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(tooltip => {
        const tooltipText = tooltip.getAttribute('data-tooltip');
        const tooltipElement = document.createElement('div');
        
        tooltipElement.className = 'invisible absolute z-10 py-1 px-2 text-xs text-white bg-gray-800 rounded-md opacity-0 transition-opacity duration-200';
        tooltipElement.textContent = tooltipText;
        
        // Position the tooltip
        tooltip.style.position = 'relative';
        tooltip.appendChild(tooltipElement);
        
        // Show tooltip on hover
        tooltip.addEventListener('mouseenter', () => {
            tooltipElement.classList.remove('invisible', 'opacity-0');
            tooltipElement.classList.add('visible', 'opacity-100');
            
            // Position the tooltip above the icon
            const rect = tooltip.getBoundingClientRect();
            tooltipElement.style.bottom = '100%';
            tooltipElement.style.left = '50%';
            tooltipElement.style.transform = 'translateX(-50%)';
            tooltipElement.style.marginBottom = '5px';
        });
        
        // Hide tooltip when not hovering
        tooltip.addEventListener('mouseleave', () => {
            tooltipElement.classList.remove('visible', 'opacity-100');
            tooltipElement.classList.add('invisible', 'opacity-0');
        });
    });
});
</script>
@endpush
